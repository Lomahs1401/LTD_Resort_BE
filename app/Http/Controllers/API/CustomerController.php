<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ranking;
use App\Models\user\Account;
use App\Models\user\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function getCustomerByAccountId()
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();

            if ($customer) {
                $ranking = Ranking::find($customer->ranking_id);
                $data[] = [
                    "avatar" => $user->avatar,
                    "username" => $user->username,
                    "email" => $user->email,
                    "name" => $customer->full_name,
                    "gender" => $customer->gender,
                    "birthday" => $customer->birthday,
                    "CMND" => $customer->CMND,
                    "address" => $customer->address,
                    "phone" => $customer->phone,
                    "ranking_point" => $customer->ranking_point,
                    "ranking_name" => $ranking->ranking_name,
                    "discount" => $ranking->discount
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

    public function updateCutomerByAccountId(Request $request)
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $data = Account::find($user->id);
        $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();
        $customerModel = Customer::find($customer->id);
        if($data && $customerModel){
            if ($request->avatar) {
                $data->avatar = $request->avatar;
                $data->update();
            }
            if ($request->full_name) {
                $customerModel->full_name = $request->full_name;
            }
            if ($request->gender) {
                $customerModel->gender = $request->gender;
            }
            if ($request->birthday) {
                $customerModel->birthday = $request->birthday;
            }
            if ($request->CMND) {
                $customerModel->CMND = $request->CMND;
            }
            if ($request->address) {
                $customerModel->address = $request->address;
            }
            if ($request->phone) {
                $customerModel->phone = $request->phone;
            }
            $customerModel->update();
            return response()->json([
                'message' => 'Update successfully!',
                'status' => 200,
                'data' => $data,
                'customer' => $customerModel,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ], 404);
        }
    }
    public function index()
    {
        $list_customers = Customer::all();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_customers' => $list_customers,
        ], 200);
    }
   public function ShowCustomerByID($id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            $account=Account::find($customer->account_id);
            $ranking = Ranking::find($customer->ranking_id);
            $data[] = [
                "avatar" => $account->avatar,
                "username" => $account->username,
                "email" => $account->email,
                "name" => $customer->full_name,
                "gender" => $customer->gender,
                "birthday" => $customer->birthday,
                "CMND" => $customer->CMND,
                "address" => $customer->address,
                "phone" => $customer->phone,
                "ranking_point" => $customer->ranking_point,
                "ranking_name" => $ranking->ranking_name,
                "discount" => $ranking->discount
            ];
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'message' => 'No ID Found',
                'status' => 404,
            ]);
        }
    }
        public function findCustomer(Request $request)
    {
        $query = Customer::query();

        // Tìm kiếm theo tên
        if ($request->has('full_name')) {
            $full_name = $request->full_nadme;
            $query->where('full_name', 'LIKE', '%' . $full_name . '%');
        }

        // Tìm kiếm theo số điện thoại
        if ($request->has('phone')) {
            $phone = $request->phone;
            $query->where('phone', $phone);
        }
        // Tìm kiếm theo địa điểm 
        if ($request->has('address')) {
            $address = $request->address;
            $query->where('address', $address);
        }
        //Tìm kiếm theo giới tính 
        if ($request->has('gender')) {
            $gender = $request->gender;
            if($gender==='Nam'){
              $query->where('gender', 'Nam');
            }elseif($gender==='Nữ'){
            $query->where('gender', 'Nữ');
            }
        }
        // Sắp xếp theo điểm
        if ($request->has('ranking_point')) {
            $ranking_point = $request->ranking_point;
            if ($ranking_point === 'asc') {
                $query->orderBy('ranking_point', 'asc');
            } elseif ($ranking_point === 'desc') {
                $query->orderBy('ranking_point', 'desc');
            }
        }

        $find = $query->get();

        if (count($find) > 0) {
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'data' => $find,
            ]);
        } else {
            
            return response()->json([
                'message' => 'Data not found!',
                'status' => 400,
                'data' => $find,
            ]);
        }
    }
    public function findBillByID($id)
    {       
            $bill_room = DB::table('bill_rooms')->where('customer_id', '=', $id)
            ->whereNotNull('pay_time')->get();
            $bill_service= DB::table('bill_services')->where('customer_id', '=', $id)
            ->whereNotNull('pay_time')->get();
            $bill_extra_service= DB::table('bill_extra_services')->where('customer_id', '=', $id)->get();
            if ($bill_room ->isEmpty() && $bill_service->isEmpty() && $bill_extra_service->isEmpty())  {
                return response()->json([
                        'message' => 'Data not found!',
                        'status' => 400,
                ]);
            } else {
                $data=[];
                foreach ($bill_room as $item) {
                    $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                    $data[]=[
                        'total_amount' => $item->total_amount,
                        'total_people' => $item->total_people,
                        'payment_method'=>$item->payment_method,
                        'pay_time'=>$item->pay_time,
                        'tax'=>$item->tax,
                        'discount'=>$item->discount,
                        'time_start' => $time->time_start,
                        'time_end' => $time->time_end,
                        'code'=>$item->id,
                    ];
                }
                $data1=[];
                foreach ($bill_service as $item1) {
                    $service = DB::table('services')->where('id', '=', $item1->service_id)->first();
                    $service_type= DB::table('service_types')->where('id', '=', $service->service_type_id)->first();
                    $data1[]=[
                        'quantity'=> $item1->quantity,
                        'total_amount' => $item1->total_amount,
                        'book_time' => $item1->book_time,
                        'payment_method'=>$item1->payment_method,
                        'pay_time'=>$item1->pay_time,
                        'tax'=>$item1->tax,
                        'discount'=>$item1->discount,
                        'service'=>$service->service_name,
                        'service_type'=>$service_type->service_type_name,
                        'code'=>$item1->id,
                    ];    
                }
                return response()->json([
                    'message' => 'Query successfully!',
                    'status' => 200,
                    'bill_room' => $data,
                    'bill_service'=>$data1,
                    'bill_extra_service'=>$bill_extra_service,
                ]);
            }
    }

    public function findHistoryBillCustomerByID()
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $customer_id = DB::table('customers')->where('account_id', '=', $user->id)->value('id');
        if($customer_id){
            $bill_room = DB::table('bill_rooms')->where('customer_id', '=', $customer_id)
            ->whereNotNull('checkout_time')->get();
            $bill_service= DB::table('bill_services')->where('customer_id', '=', $customer_id)
            ->whereNotNull('checkin_time')->get();
            $bill_extra_service= DB::table('bill_extra_services')->where('customer_id', '=', $customer_id)->get();
            if ($bill_room ->isEmpty() && $bill_service->isEmpty() && $bill_extra_service->isEmpty())  {
                return response()->json([
                        'message' => 'Data not found!',
                        'status' => 400,
                ]);
            } else {
                
                $data=[];
                foreach ($bill_room as $item) {
                    $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                    $data[]=[
                        'total_amount' => $item->total_amount,
                        'total_people' => $item->total_people,
                        'payment_method'=>$item->payment_method,
                        'pay_time'=>$item->pay_time,
                        'tax'=>$item->tax,
                        'discount'=>$item->discount,
                        'time_start' => $time->time_start,
                        'time_end' => $time->time_end,
                        'code'=>$item->id,
                    ];}
                    $data1=[];
                    foreach ($bill_service as $item1) {
                        $service = DB::table('services')->where('id', '=', $item1->service_id)->first();
                        $service_type= DB::table('service_types')->where('id', '=', $service->service_type_id)->first();
                        $data1[]=[
                            'quantity'=> $item1->quantity,
                            'total_amount' => $item1->total_amount,
                            'book_time' => $item1->book_time,
                            'payment_method'=>$item1->payment_method,
                            'pay_time'=>$item1->pay_time,
                            'tax'=>$item1->tax,
                            'discount'=>$item1->discount,
                            'service'=>$service->service_name,
                            'service_type'=>$service_type->service_type_name,
                            'code'=>$item1->id,
                        ];    
                }
                return response()->json([
                    'message' => 'Query successfully!',
                    'status' => 200,
                    'bill_room' => $data,
                    'bill_service'=>$data1,
                    'bill_extra_service'=>$bill_extra_service,
                ]);
            }
          }else {
            return response()->json([
                'message' => 'Data not found by token!',
                'status' => 404,
            ], 404);
        }
    }
    public function findBookBillCustomerByID()
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $customer_id = DB::table('customers')->where('account_id', '=', $user->id)->value('id');
        if($customer_id){
            $bill_room = DB::table('bill_rooms')->where('customer_id', '=', $customer_id)
            ->whereNotNull('pay_time')
            ->whereNull('checkout_time')->get();
            $bill_service= DB::table('bill_services')->where('customer_id', '=', $customer_id)
            ->whereNotNull('pay_time')
            ->whereNull('checkin_time')->get();
            if ($bill_room ->isEmpty() && $bill_service->isEmpty())  {
                return response()->json([
                        'message' => 'Data not found!',
                        'status' => 400,
                ]);
            } else {
                
                $data=[];
                foreach ($bill_room as $item) {
                    $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                    $data[]=[
                        'total_amount' => $item->total_amount,
                        'total_people' => $item->total_people,
                        'payment_method'=>$item->payment_method,
                        'pay_time'=>$item->pay_time,
                        'tax'=>$item->tax,
                        'discount'=>$item->discount,
                        'time_start' => $time->time_start,
                        'time_end' => $time->time_end,
                        'code'=>$item->id,
                    ];}
                    $data1=[];
                    foreach ($bill_service as $item1) {
                        $service = DB::table('services')->where('id', '=', $item1->service_id)->first();
                        $service_type= DB::table('service_types')->where('id', '=', $service->service_type_id)->first();
                        $data1[]=[
                            'quantity'=> $item1->quantity,
                            'total_amount' => $item1->total_amount,
                            'book_time' => $item1->book_time,
                            'payment_method'=>$item1->payment_method,
                            'pay_time'=>$item1->pay_time,
                            'tax'=>$item1->tax,
                            'discount'=>$item1->discount,
                            'service'=>$service->service_name,
                            'service_type'=>$service_type->service_type_name,
                            'code'=>$item1->id,
                        ];    
                }
                return response()->json([
                    'message' => 'Query successfully!',
                    'status' => 200,
                    'bill_room' => $data,
                    'bill_service'=>$data1,
                 
                ]);
            }
            
        }else {
            return response()->json([
                'message' => 'Data not found by token!',
                'status' => 404,
            ], 404);
        }
    }
    public function getTotalAmount($id)
    {
        $totalAmount = 0;

        // Tính tổng total_amount của bảng bill_room
        $billRooms = DB::table('bill_rooms')->where('customer_id', '=', $id)
            ->whereNotNull('pay_time')
            ->get();
        $totalAmount += $billRooms->sum('total_amount');

        // Tính tổng total_amount của bảng bill_service
        $billServices = DB::table('bill_services')->where('customer_id', '=', $id)
            ->whereNotNull('pay_time')
            ->get();
        $totalAmount += $billServices->sum('total_amount');

        // Tính tổng total_amount của bảng bill_extra_service
        $billExtraServices =DB::table('bill_extra_services')->where('customer_id', '=', $id)->get();
        $totalAmount += $billExtraServices->sum('total_amount');

        return response()->json(['total_amount' => $totalAmount]);
    }

    public function getRankingNameByAccountId($id)
    {
        $customer_id = DB::table('customers')->where('account_id', '=', $id)->value('id');
        $ranking_id = DB::table('customers')->where('id', '=', $customer_id)->value('ranking_id');
        $ranking_name = DB::table('rankings')->where('id', '=', $ranking_id)->value('ranking_name');

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'ranking_name' => $ranking_name,
        ], 200);
    }
}

