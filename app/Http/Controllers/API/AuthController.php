<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\user\Account;
use App\Models\user\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request){
        // validate form
        $validator = Validator::make($request->all(),[ 
            'username'=> 'required',
            'email'=>'required|email|unique:accounts',
            'password'=>'required|min:6',
            'confirm_password'=> 'required|same:password',
        ]);

        if($validator->fails()){
            $errors = $validator->errors();
            
            $response = [
                'status_code' => 400,
                'message' => [
                    'username' => $errors->first('username'),
                    'email' => $errors->first('email'),
                    'password' => $errors->first('password'),
                    'confirm_password' => $errors->first('confirm_password'),
                ],
            ];

            return response()->json($response,400);
        }

        // Create new account
        $account = Account::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $request->avatar,
            'enabled' => $request->enabled|'1',
            'role_id' => $request->role_id|'3'
        ]);
 
        Customer::create([
            'ranking_point' => 0,
            'account_id' => $account->id,
            'ranking_id' => 1,
        ]);

        // Get a JWT
        $token = Auth::login($account);

        return response()->json([
            'status_code' => 201,
            'message' => 'User created successfully!',
            'user' => $account,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Invalid form data',
                'error' => $validator->errors(), 
            ], 400);
        }

        $credentials = request(['email', 'password']);
        $account = Account::where('email', $request->email)->first();

        if (!$account) {
            // Tài khoản không tồn tại
            return response()->json([
                'status_code' => 401,
                'message' => 'Login failed!',
                'error' => 'Unauthorized',
            ], 401);
        }

        if ($account->enabled == 0) {
            // Tài khoản bị vô hiệu hóa
            return response()->json([
                'status_code' => 401,
                'message' => 'Login failed!',
                'error' => 'Account is disabled',
            ], 401);
        }
        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status_code' => 401,
                'message' => 'Login failed!',
                'error' => 'Unauthorized', 
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'status_code' => 200,
            'message' => 'Logout successfully!',
        ], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $role_user = DB::table('roles')->where('id', '=', auth()->user()->role_id)->value('role_name');
        if ($role_user === "ROLE_CUSTOMER") {
            $customer = Customer::where('account_id', auth()->user()->id)->first();
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'message' => 'Login successfully!',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => array_merge(auth()->user()->toArray(), [
                    'role_name' => $role_user,
                    'customer_id' => $customer->id,
                    'full_name' => $customer->full_name
                ]),
            ]);
        } else {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'message' => 'Login successfully!',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => array_merge(auth()->user()->toArray(), [
                    'role_name' => $role_user,
                ]),
            ]);
        }
    }
}
