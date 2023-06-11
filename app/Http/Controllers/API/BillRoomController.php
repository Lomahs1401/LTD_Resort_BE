<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\room\BillRoom;
use App\Models\room\ReservationRoom;
use App\Models\room\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillRoomController extends Controller
{
    public function findBillRoom()
    {
        $bill_room = DB::table('bill_rooms')
            ->whereNotNull('pay_time')
            ->whereNull('checkout_time')
            ->get();

        if ($bill_room->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ]);
        } else {
            $data = [];
            foreach ($bill_room as $item) {
                $name = DB::table('customers')->where('id', '=', $item->customer_id)->first();
                $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                $data[] = [
                    'full_name' => $name->full_name,
                    'birthday' => $name->birthday,
                    'phone' => $name->phone,
                    'total_amount' => $item->total_amount,
                    'total_people' => $item->total_people,
                    'payment_method' => $item->payment_method,
                    'pay_time' => $item->pay_time,
                    'checkin_time' => $item->checkin_time,
                    'checkout_time' => $item->checkout_time,
                    'cancel_time' => $item->cancel_time,
                    'tax' => $item->tax,
                    'discount' => $item->discount,
                    'time_start' => $time->time_start,
                    'time_end' => $time->time_end,
                    'id' => $item->id,
                ];
            }

            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'bill_room' => $data,
            ]);
        }
    }
    public function findHistoryRoom()
    {
        $bill_room = DB::table('bill_rooms')
            ->whereNotNull('checkout_time')
            ->get();

        if ($bill_room->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ]);
        } else {
            $data = [];
            foreach ($bill_room as $item) {
                $name = DB::table('customers')->where('id', '=', $item->customer_id)->first();
                $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                $data[] = [
                    'full_name' => $name->full_name,
                    'birthday' => $name->birthday,
                    'phone' => $name->phone,
                    'total_amount' => $item->total_amount,
                    'total_people' => $item->total_people,
                    'payment_method' => $item->payment_method,
                    'pay_time' => $item->pay_time,
                    'checkin_time' => $item->checkin_time,
                    'checkout_time' => $item->checkout_time,
                    'cancel_time' => $item->cancel_time,
                    'tax' => $item->tax,
                    'discount' => $item->discount,
                    'time_start' => $time->time_start,
                    'time_end' => $time->time_end,
                    'id' => $item->id,
                ];
            }

            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'bill_room' => $data,
            ]);
        }
    }
    public function findCancelRoom()
    {
        $bill_room = DB::table('bill_rooms')
            ->whereNotNull('cancel_time')
            ->get();

        if ($bill_room->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ]);
        } else {
            $data = [];
            foreach ($bill_room as $item) {
                $name = DB::table('customers')->where('id', '=', $item->customer_id)->first();
                $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                $data[] = [
                    'full_name' => $name->full_name,
                    'birthday' => $name->birthday,
                    'phone' => $name->phone,
                    'total_amount' => $item->total_amount,
                    'total_people' => $item->total_people,
                    'payment_method' => $item->payment_method,
                    'pay_time' => $item->pay_time,
                    'checkin_time' => $item->checkin_time,
                    'checkout_time' => $item->checkout_time,
                    'cancel_time' => $item->cancel_time,
                    'tax' => $item->tax,
                    'discount' => $item->discount,
                    'time_start' => $time->time_start,
                    'time_end' => $time->time_end,
                    'id' => $item->id,
                ];
            }

            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'bill_room' => $data,
            ]);
        }
    }
    public function findBillRoomDetail($id)
    {
        $name = DB::table('reservation_rooms')->where('bill_room_id', '=', $id)->get();
        if ($name->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ]);
        } else {
            $data = [];
            foreach ($name as $item) {
                $room = DB::table('rooms')->where('id', '=', $item->room_id)->first();
                $room_type = DB::table('room_types')->where('id', '=', $room->room_type_id)->first();
                $area = DB::table('areas')->where('id', '=', $room->area_id)->first();
                $floor = DB::table('floors')->where('id', '=', $room->floor_id)->first();
                $data[] = [
                    'id' => $room->id,
                    'room_name' => $room->room_name,
                    'room_type' => $room_type->room_type_name,
                    'area' => $area->area_name,
                    'floor' => $floor->floor_name,
                    'price' => $room_type->price,
                    'point_ranking' => $room_type->point_ranking,
                    'room_type_id' => $room->room_type_id,
                ];
            }

            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'bill_room_detail' => $data,
            ]);
        }
    }
    
    public function getCancelBillRoomByCustomer($id)
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();
        if ($customer) {
            $bill_room = BillRoom::find($id);
            $bill_room->cancel_time = Carbon::now();
            $bill_room->update();
            

           
                return response()->json([
                    'status' => 200,
                    'message' => 'bill confirm cancel by customer',
                    'bill-room' => $bill_room,
                ]);
            }
        }
        }
        public function getGetCheckinRoom(Request $request,$id)
        {
            $validator = Validator::make($request->all(), [
               
                'bill_code' =>  'required'
            ]);
            if ($validator->fails()) {
                $response = [
                    'status_code' => 400,
                    'message' => $validator->errors(),
                ];
                return response()->json($response, 400);
            }
            $bill_code = $request->bill_code;
                $bill_room = BillRoom::find($id);
                if($bill_room->bill_code ==  $bill_code){
                $bill_room->checkin_time = Carbon::now();
                $bill_room->update();
                  return response()->json([
                        'status' => 200,
                        'message' => 'bill checkin Successfully',
                        'bill-room' => $bill_room,
                    ]);
                }else{
                    return response()->json([
                        'status' => 404,
                        'message' => 'bill code not found',
                        'bill-room' => $bill_room,
                    ]);
                }
            }
            public function getGetCheckoutRoom(Request $request,$id)
            {
                $validator = Validator::make($request->all(), [
                   
                    'bill_code' =>  'required'
                ]);
                if ($validator->fails()) {
                    $response = [
                        'status_code' => 400,
                        'message' => $validator->errors(),
                    ];
                    return response()->json($response, 400);
                }
               
                    $bill_room = BillRoom::find($id);
                    if($bill_room->checkin_time != null){
                    $bill_room->checkout_time = Carbon::now();
                    $bill_room->update();
                      return response()->json([
                            'status' => 200,
                            'message' => 'bill checkout Successfully',
                            'bill-room' => $bill_room,
                        ]);
                    }else{
                        return response()->json([
                            'status' => 404,
                            'message' => 'bill not check out',
                            'bill-room' => $bill_room,
                        ]);
                    }
                }
            
    public function deleteBillRoom($id)
    {
        // Lấy danh sách các bill_room cần xóa
        $billRoom = BillRoom::find($id);
        if($billRoom->cancel_time != null){
            // Xóa các dữ liệu liên quan (ReservationRooms, ...) trước khi xóa bill_room
            $reservation_room = DB::table('reservation_rooms')->where('bill_room_id', '=', $id)->delete();
            // Xóa bill_room
            $billRoom->delete();

        if (!$billRoom) {
            return response()->json([
                'status' => 404,
                'message' => 'Bill not found',

            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'Delete bill room Successfully',

            ]);
        }
    }else{
        return response()->json([
            'status' => 404,
            'message' => 'Bill not found',

        ]);
    }
    }
}
