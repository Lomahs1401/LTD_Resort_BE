<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\user\Admin;
use App\Models\user\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // public function index()
    // {
    //     $listAdmin = Admin::all();
    //     return response()->json([
    //         'message' => 'Query successfully!',
    //         'status' => 200,
    //         'list_accounts' => $listAdmin
    //     ]);
    // }
    
    public function searchByParams($search)
    {
        if ($search) {
            $result  = Admin::where('full_name', 'LIKE', "%{$search}%")->get();

            if (count($result) > 0) {
                return response()->json([
                    'status' => 200,
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Not search'
                ]);
            }
        }
    }
    public function getAdminByAccountId()
    { 
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $admin = DB::table('admins')->where('account_id', '=', $user->id)->first();
           
            if ($admin) {
                $position = Position::find($admin->position_id);
                if ($position) {
                    $department = Department::find($position->department_id);
    
                    $data = [
                        "avatar"=>$user->avatar,
                        "username" => $user->username,
                        "email"=> $user->email,
                        "image"=> $admin->image,
                        "name" => $admin->full_name,
                        "gender" => $admin->gender,
                        "birthday" => $admin->birthday,
                        "CMND" => $admin->CMND,
                        "address" => $admin->address,
                        "phone" => $admin->phone,
                        "position_name" => $position->position_name,
                        "department_name" => $department->department_name
                    ];
    
                return response()->json([
                    'message' => 'Query successfully!',
                    'status' => 200,
                    'customer' => $data,
                ], 200);
                } else {
                    return response()->json([
                        'message' => 'Data not found!',
                        'status' => 404,
                    ], 404);
                }
            }
        }
    }
    public function updateAdminByAccountId(Request $request)
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $data = Account::find($user->id);
        $admin = DB::table('admins')->where('account_id', '=', $user->id)->first();
        $adminModel = Admin::find($admin->id);
        if($data && $adminModel){
            if ($request->avatar) {
                $data->avatar = $request->avatar;
                $data->update();
            }
            if ($request->full_name) {
                $adminModel->full_name = $request->full_name;
            }
            if ($request->gender) {
                $adminModel->gender = $request->gender;
            }
            if ($request->birthday) {
                $adminModel->birthday = $request->birthday;
            }
            if ($request->CMND) {
                $adminModel->CMND = $request->CMND;
            }
            if ($request->address) {
                $adminModel->address = $request->address;
            }
            if ($request->phone) {
                $adminModel->phone = $request->phone;
            }
            if ($request->image) {
                $adminModel->image = $request->image;
            }
            $adminModel->update();
            return response()->json([
                'message' => 'Update successfully!',
                'status' => 200,
                'data' => $data,
                'customer' => $adminModel,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ], 404);
        }

    }
    public function adminFindID($id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            $position = Position::find($admin->position_id);
            if ($position) {
                $department = Department::find($position->department_id);

                $data = [
                    "id" => $admin->id,
                    "image"=> $admin->image,
                    "name" => $admin->full_name,
                    "gender" => $admin->gender,
                    "birthday" => $admin->birthday,
                    "CMND" => $admin->CMND,
                    "address" => $admin->address,
                    "phone" => $admin->phone,
                    "position_name" => $position->position_name,
                    "department_name" => $department->department_name

                ];
                return response()->json([
                    'status' => 200,
                    'message' => 'OK',
                    'data' => $data
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No ID Found',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
   

    /**
     * Update the specified resource in storage.
     */
 
}
