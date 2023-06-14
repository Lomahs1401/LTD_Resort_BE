<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ranking;
use App\Models\Account;
use App\Models\Customer;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BillRoom;
use App\Models\BillService;
use App\Models\ReservationRoom;
use Carbon\Carbon;

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
                $data = [
                    "avatar" => $user->avatar,
                    "username" => $user->username,
                    "email" => $user->email,
                    "id" => $customer->id,
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
        if ($data && $customerModel) {
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
        } else {
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
            $account = Account::find($customer->account_id);
            $ranking = Ranking::find($customer->ranking_id);
            $data = [
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
            if ($gender === 'Nam') {
                $query->where('gender', 'Nam');
            } elseif ($gender === 'Nữ') {
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
                'status' => 404,
                'data' => $find,
            ]);
        }
    }
    public function findBillByID($id)
    {
        $bill_room = DB::table('bill_rooms')->where('customer_id', '=', $id)
            ->whereNotNull('pay_time')->get();
        $bill_service = DB::table('bill_services')->where('customer_id', '=', $id)
            ->whereNotNull('pay_time')->get();
        $bill_extra_service = DB::table('bill_extra_services')->where('customer_id', '=', $id)->get();
        if ($bill_room->isEmpty() && $bill_service->isEmpty() && $bill_extra_service->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ]);
        } else {
            $data = [];
            foreach ($bill_room as $item) {
                $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                $data[] = [
                    'id' => $item->id,
                    'total_amount' => $item->total_amount,
                    'total_people' => $item->total_people,
                    'payment_method' => $item->payment_method,
                    'pay_time' => $item->pay_time,
                    'tax' => $item->tax,
                    'discount' => $item->discount,
                    'time_start' => $time->time_start,
                    'time_end' => $time->time_end,

                ];
            }
            $data1 = [];
            foreach ($bill_service as $item1) {
                $service = DB::table('services')->where('id', '=', $item1->service_id)->first();
                $service_type = DB::table('service_types')->where('id', '=', $service->service_type_id)->first();
                $data1[] = [
                    'id' => $item1->id,
                    'quantity' => $item1->quantity,
                    'total_amount' => $item1->total_amount,
                    'book_time' => $item1->book_time,
                    'payment_method' => $item1->payment_method,
                    'pay_time' => $item1->pay_time,
                    'tax' => $item1->tax,
                    'discount' => $item1->discount,
                    'service' => $service->service_name,
                    'service_type' => $service_type->service_type_name,

                ];
            }
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'bill_room' => $data,
                'bill_service' => $data1,
                'bill_extra_service' => $bill_extra_service,
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
        if ($customer_id) {
            $bill_room = DB::table('bill_rooms')->where('customer_id', '=', $customer_id)
                ->whereNotNull('checkout_time')->get();
            $bill_service = DB::table('bill_services')->where('customer_id', '=', $customer_id)
                ->whereNotNull('checkin_time')->get();
            $bill_extra_service = DB::table('bill_extra_services')->where('customer_id', '=', $customer_id)->get();
            if ($bill_room->isEmpty() && $bill_service->isEmpty() && $bill_extra_service->isEmpty()) {
                return response()->json([
                    'message' => 'Data not found!',
                    'status' => 404,
                ]);
            } else {

                $data = [];
                foreach ($bill_room as $item) {
                    $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                    $data[] = [
                        'id' => $item->id,
                        'total_amount' => $item->total_amount,
                        'total_room' => $item->total_room,
                        'total_people' => $item->total_people,
                        'payment_method' => $item->payment_method,
                        'pay_time' => $item->pay_time,
                        'tax' => $item->tax,
                        'discount' => $item->discount,
                        'bill_code' => $item->bill_code,
                        'time_start' => $time->time_start,
                        'time_end' => $time->time_end,
                    ];
                }
                $data1 = [];
                foreach ($bill_service as $item1) {
                    $service = DB::table('services')->where('id', '=', $item1->service_id)->first();
                    $service_type = DB::table('service_types')->where('id', '=', $service->service_type_id)->first();
                    $data1[] = [
                        'id' => $item1->id,
                        'quantity' => $item1->quantity,
                        'total_amount' => $item1->total_amount,
                        'book_time' => $item1->book_time,
                        'payment_method' => $item1->payment_method,
                        'pay_time' => $item1->pay_time,
                        'tax' => $item1->tax,
                        'discount' => $item1->discount,
                        'bill_code' => $item1->bill_code,
                        'service_id' => $item1->service_id,
                        'service' => $service->service_name,
                        'service_type' => $service_type->service_type_name,

                    ];
                }
                return response()->json([
                    'message' => 'Query successfully!',
                    'status' => 200,
                    'bill_room' => $data,
                    'bill_service' => $data1,
                    'bill_extra_service' => $bill_extra_service,
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Data not found by token!',
                'status' => 401,
            ], 401);
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
        if ($customer_id) {
            $bill_room = DB::table('bill_rooms')->where('customer_id', '=', $customer_id)
                ->whereNotNull('pay_time')
                ->whereNull('checkout_time')->get();
            $bill_service = DB::table('bill_services')->where('customer_id', '=', $customer_id)
                ->whereNotNull('pay_time')
                ->whereNull('checkin_time')->get();
            if ($bill_room->isEmpty() && $bill_service->isEmpty()) {
                return response()->json([
                    'message' => 'Data not found!',
                    'status' => 404,
                ]);
            } else {

                $data = [];
                foreach ($bill_room as $item) {
                    $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                    $data[] = [
                        'id' => $item->id,
                        'total_amount' => $item->total_amount,
                        'total_people' => $item->total_people,
                        'payment_method' => $item->payment_method,
                        'pay_time' => $item->pay_time,
                        'tax' => $item->tax,
                        'discount' => $item->discount,
                        'bill_code' => $item->bill_code,
                        'time_start' => $time->time_start,
                        'time_end' => $time->time_end,

                    ];
                }
                $data1 = [];
                foreach ($bill_service as $item1) {
                    $service = DB::table('services')->where('id', '=', $item1->service_id)->first();
                    $service_type = DB::table('service_types')->where('id', '=', $service->service_type_id)->first();
                    $data1[] = [
                        'id' => $item1->id,
                        'quantity' => $item1->quantity,
                        'total_amount' => $item1->total_amount,
                        'book_time' => $item1->book_time,
                        'payment_method' => $item1->payment_method,
                        'pay_time' => $item1->pay_time,
                        'tax' => $item1->tax,
                        'discount' => $item1->discount,
                        'bill_code' => $item1->bill_code,
                        'service' => $service->service_name,
                        'service_type' => $service_type->service_type_name,

                    ];
                }
                return response()->json([
                    'message' => 'Query successfully!',
                    'status' => 200,
                    'bill_room' => $data,
                    'bill_service' => $data1,

                ]);
            }
        } else {
            return response()->json([
                'message' => 'Data not found by token!',
                'status' => 401,
            ], 401);
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
        $billExtraServices = DB::table('bill_extra_services')->where('customer_id', '=', $id)->get();
        $totalAmount += $billExtraServices->sum('total_amount');

        return response()->json(['total_amount' => $totalAmount]);
    }

    public function getRankingPoint($id)
    {
        $ranking_point = 0;

        $bill_room = DB::table('bill_rooms')->where('customer_id', '=', $id)
            ->whereNotNull('pay_time')
            ->get();
        $bill_service = DB::table('bill_services')->where('customer_id', '=', $id)
            ->whereNotNull('pay_time')->get();
        foreach ($bill_room as $item) {
            $reservation = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->get();
            foreach ($reservation as $item1) {

                $room = DB::table('rooms')->where('id', '=', $item1->room_id)->get();
                foreach ($room as $item2) {
                    // $room_type = DB::table('room_types')->where('id', '=', $item2->room_type_id)->get();
                    $room_type = RoomType::find($item2->room_type_id);
                    $ranking_point += $room_type->point_ranking;
                }
            }
        }
        foreach ($bill_service as $item1) {
            $service = DB::table('services')->where('id', '=', $item1->service_id)->first();
            $ranking_point += ($service->point_ranking * $item1->quantity);
        }
        $customer = Customer::find($id);

        $ranking = DB::table('rankings')->where('point_start', '<=', $ranking_point)
            ->max('id');
        $customer = Customer::find($id);
        if ($customer) {
            $customer->ranking_point = $ranking_point;
            $customer->ranking_id = $ranking;
            $customer->update();
            return response()->json([
                'message' => 'Update successfully!',
                'status' => 200,
                'customer' => $customer,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ], 404);
        }
    }
    public function getPayBillSuccess(Request $request, $time_start, $time_end)
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();

            if ($customer) {
                $total_amount = 0;
                $total_people = 0;
                $total_room = DB::table('reservation_rooms')
                    ->where('customer_id', '=', $customer->id)
                    ->where('status', '=', '0')
                    ->where('time_start', '=', $time_start)
                    ->where('time_end', '=', $time_end)->count();
                $reservation_rooms = DB::table('reservation_rooms')
                    ->where('customer_id', '=', $customer->id)
                    ->where('status', '=', '0')
                    ->where('time_start', '=', $time_start)
                    ->where('time_end', '=', $time_end)->get();
                $price = 0;
                foreach ($reservation_rooms as $item1) {

                    $room = DB::table('rooms')->where('id', '=', $item1->room_id)->get();
                    foreach ($room as $item2) {
                        $room_type = RoomType::find($item2->room_type_id);
                        $total_people += $room_type->number_customers;
                        $price += $room_type->price;
                    }
                }
                $ranking = DB::table('rankings')->where('id', '=', $customer->ranking_id)->first();
                $startDate = Carbon::parse($time_start);
                $endDate = Carbon::parse($time_end);
                $numberOfDays = $startDate->diffInDays($endDate);
                $total_amount += $numberOfDays * $price * (1 - $ranking->discount) * (1 + 0.05);

                $bill_room = BillRoom::create([
                    'total_amount' => $total_amount,
                    'total_room' =>  $total_room,
                    'total_people' => $total_people,
                    'payment_method' =>  'online',
                    'pay_time' => Carbon::now(),
                    'tax' => '0.05',
                    'bill_code' => $request->bill_code,
                    'discount' => $ranking->discount,
                    'customer_id' => $customer->id,
                ]);


                $bill_service = DB::table('bill_services')->where('customer_id', '=', $customer->id)
                    ->whereNull('pay_time')
                    ->get();

                foreach ($bill_service as $item2) {
                    $pay = BillService::find($item2->id);
                    $pay->pay_time = Carbon::now();
                    $pay->bill_code = $request->bill_code;
                    $pay->update();
                }
                if (!$bill_room && $bill_service->isEmpty()) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Bill not found',

                    ]);
                } else {
                    foreach ($reservation_rooms as $item2) {
                        $reservation_room = ReservationRoom::find($item2->id);
                        $reservation_room->status = '1';
                        $reservation_room->bill_room_id = $bill_room->id;
                        $reservation_room->update();
                    }
                    return response()->json([
                        'status' => 200,
                        'message' => 'Pay bill Successfully',
                        'reservation_room' => $reservation_room,
                        'bill-room' => $bill_room,
                        'bill-service' => $bill_service,
                    ]);
                }
            }
        }
    }
    // public function getPayBillSuccess(Request $request)
    // {

    //     $user = auth()->user();
    //     // Kiểm tra token hợp lệ và người dùng đã đăng nhập
    //     if (!$user) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     } else {
    //         $customer_id = DB::table('customers')->where('account_id', '=', $user->id)->first();

    //         if ($customer_id) {
    //             $bill_room = DB::table('bill_rooms')->where('customer_id', '=', $customer_id->id)
    //                 ->whereNull('pay_time')
    //                 ->get();

    //             foreach ($bill_room as $item) {
    //                 $pay = BillRoom::find($item->id);
    //                 $pay->pay_time = Carbon::now();
    //                 $pay->bill_code = $request->bill_code;
    //                 $pay->update();

    //                 $status = DB::table('reservation_rooms')->where('id', '=', $item->id)->get();
    //                 foreach ($status as $item1) {
    //                     $reservation_room = ReservationRoom::find($item1->id);
    //                     $reservation_room->status = '0';
    //                     $reservation_room->update();
    //                 }
    //             }

    //             $bill_service = DB::table('bill_services')->where('customer_id', '=', $customer_id->id)
    //                 ->whereNull('pay_time')
    //                 ->get();

    //             foreach ($bill_service as $item2) {
    //                 $pay = BillService::find($item2->id);
    //                 $pay->pay_time = Carbon::now();
    //                 $pay->bill_code = $request->bill_code;
    //                 $pay->update();
    //             }
    //             if ($bill_room->isEmpty() && $bill_service->isEmpty()) {
    //                 return response()->json([
    //                     'status' => 404,
    //                     'message' => 'Bill not found',

    //                 ]);
    //             } else {
    //                 return response()->json([
    //                     'status' => 200,
    //                     'message' => 'Pay bill Successfully',

    //                 ]);
    //             }
    //         }
    //     }
    // }
}
