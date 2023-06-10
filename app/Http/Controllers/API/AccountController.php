<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Mail\ResetCodeEmail;
use Illuminate\Http\Request;
use App\Models\user\Account;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
  

    public function changePassword(Request $request)
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }
        // Kiểm tra và cập nhật dữ liệu chỉ khi người dùng đúng là chủ sở hữu
        $data = Account::findOrFail($user->id);
        $currentPassword = $request->current_password;
        $newPassword = $request->new_password;
        // Cập nhật mật khẩu mới cho người dùng
        if ($data) {
            // Kiểm tra mật khẩu hiện tại của người dùng
            if (!Hash::check($currentPassword, $data->password)) {
                return response()->json(['message' => 'Current password not match!'], 401);
            }
            $data->password = Hash::make($newPassword);
            $data->save();
            if ($data) {
                return response()->json([
                    'message' => 'Change password successfully',
                    'status' => 200,
                    'account' => $data,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Update password failed!',
                    'status' => 400,
                    'account' => $data,
                ], 400);
            }
        }
    }


    public function requestResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => 'Invalid data',
                    'errors' => $validator->errors()
                ],
                400
            );
        }
        // Kiểm tra email có tồn tại trong cơ sở dữ liệu
        $user = Account::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        // Tạo mã code và lưu vào cơ sở dữ liệu
        $code = Str::random(6);
        $user->reset_code = $code;
        $user->reset_code_expires_at = Carbon::now()->addMinutes(5);
        $user->reset_code_attempts = 0;
        $user->save();

        // Gửi email chứa mã code đến người dùng
        Mail::to($user->email)->send(new ResetCodeEmail($user));

        return response()->json(['message' => 'Reset code sent to your email']);
    }

    public function resetVerifyCode(Request $request, $email)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => 'Verify code is required',
                    'errors' => $validator->errors()
                ],
                400
            );
        }

        // Tìm người dùng dựa trên mã code
        $user = Account::where('email', $email)
            ->where('reset_code_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired reset code'], 400);
        }

        // Kiểm tra số lần nhập mã code sai
        if ($user->reset_code_attempts >= 5) {
            return response()->json(['message' => 'Reset code expired'], 400);
        }

        // Kiểm tra mã code nhập vào
        if ($request->code !== $user->reset_code) {
            $user->reset_code_attempts++;
            $user->save();
            $attemptsLeft = 5 - $user->reset_code_attempts;
            return response()->json([
                'message' => 'Invalid Reset Code',
                'Your remaining password attempts are' => $attemptsLeft  
            ], 400);
        } else {
            return response()->json([
                'message' => 'Verify Code True',
                'status' => 200,
                'account' => $user,
            ], 200);
        }
    }
    
    public function resetPassword(Request $request, $email, $code)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => 'Invalid data',
                    'errors' => $validator->errors()
                ],
                400
            );
        }
        // Tìm người dùng dựa trên mã code
        $user = Account::where('email', $email)
            ->where('reset_code', $code)
            ->first();
    

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired reset code'], 400);
        }

        // Cập nhật mật khẩu mới và xóa mã code
        $user->password = Hash::make($request->password);
        $user->reset_code = null;
        $user->reset_code_expires_at = null;
        $user->reset_code_attempts = null;
        $user->save();

        return response()->json(['message' => 'Password reset successful']);
    }
}
