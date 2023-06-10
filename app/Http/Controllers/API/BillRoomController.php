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
                    'room_type_id' => $room_type->id,
                ];
            }

            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'bill_room_detail' => $data,
            ]);
        }
    }
    public function storeBillRoom(Request $request, $time_start, $time_end)
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $validator = Validator::make($request->all(), [
            //  'time_start',
            //  'time_end'

        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }
        $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();
        $ranking = DB::table('rankings')->where('id', '=', $customer->ranking_id)->first();
        if ($customer) {
            $bill_room = BillRoom::create([
                'total_amount' => '0',
                'total_room' =>  '0',
                'total_people' => '0',
                'payment_method' =>  'Online',
                // 'pay_time'=>'0',
                'tax' => '0.05',
                'discount' => $ranking->discount,
                'customer_id' => $customer->id,
            ]);

            if ($bill_room) {
                $room_ids = explode(',', $request->input('room_id'));
                $data = [];

                foreach ($room_ids as $room_id) {
                    $data[] = $room_id;
                }
                $data1 = [];
                foreach ($data as $item) {
                    $reservation_room =  ReservationRoom::create([
                        'time_start' => $time_start,
                        'time_end' => $time_end,
                        'status' => '0',
                        'room_id' => $item,
                        'bill_room_id' => $bill_room->id,
                    ]);
                    $data1[] = $reservation_room;
                }
                $this->calculateBillRoomFields($bill_room);
                // $this->scheduleDataCleanup($bill_room);
                return response()->json([
                    'status' => 200,
                    'message' => 'bill Added Successfully',
                    'reservation_room' => $data1,
                    // 'customer'=> $room_ids,
                    'bill-room' => $bill_room,
                ]);
            }
        }
    }
    private function calculateBillRoomFields(BillRoom $bill_room)
    {

        $total_room = DB::table('reservation_rooms')->where('bill_room_id', '=', $bill_room->id)->count();
        $total_amount = 0;
        $total_people = 0;
        $price = 0;
        $reservation_rooms = DB::table('reservation_rooms')->where('bill_room_id', '=', $bill_room->id)->get();
        foreach ($reservation_rooms as $item1) {
            $room = DB::table('rooms')->where('id', '=', $item1->room_id)->get();
            foreach ($room as $item2) {
                $room_type = RoomType::find($item2->room_type_id);
                $total_people += $room_type->number_customers;
                $price += $room_type->price;
            }
        }
        $reservation_time = DB::table('reservation_rooms')->where('bill_room_id', '=', $bill_room->id)->first();
        $startDate = Carbon::parse($reservation_time->time_start);
        $endDate = Carbon::parse($reservation_time->time_end);
        $numberOfDays = $startDate->diffInDays($endDate);
        $total_amount = $numberOfDays * $price * (1 - $bill_room->discount) * (1 + $bill_room->tax);;

        $bill_room->total_amount = $total_amount;
        $bill_room->total_room = $total_room;
        $bill_room->total_people = $total_people;
        $bill_room->save();
    }

    public function deleteBillRoom()
    {
        // Lấy danh sách các bill_room cần xóa
        $billRooms = BillRoom::whereNull('pay_time')
            ->where('created_at', '<=', Carbon::now()->subMinutes(15))
            ->get();
        foreach ($billRooms as $billRoom) {
            // Xóa các dữ liệu liên quan (ReservationRooms, ...) trước khi xóa bill_room
            $reservation_room = DB::table('reservation_rooms')->where('bill_room_id', '=', $billRoom->id)->delete();
            // Xóa bill_room
            $billRoom->delete();
        };
        if ($billRooms->isEmpty()) {
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
    }
}
