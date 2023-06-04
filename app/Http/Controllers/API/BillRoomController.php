<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\room\BillRoom;
use Illuminate\Http\Request;

class BillRoomController extends Controller
{
    public function findBillRoom()
    {       
             $bill_room = DB::table('bill_rooms')
            ->whereNotNull('pay_time')
            ->whereNull('checkout_time')
            ->get();
           
            if ($bill_room ->isEmpty())  {
                return response()->json([
                        'message' => 'Data not found!',
                        'status' => 404,
                ]);
            } else {
                $data=[];
                foreach ($bill_room as $item) {
                    $name = DB::table('customers')->where('id', '=', $item->customer_id)->first();
                    $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                    $data[]=[
                        'full_name'=>$name->full_name,
                        'birthday'=>$name->birthday,
                        'phone'=>$name->phone,
                        'total_amount' => $item->total_amount,
                        'total_people' => $item->total_people,
                        'payment_method'=>$item->payment_method,
                        'pay_time'=>$item->pay_time,
                        'checkin_time'=>$item->checkin_time,
                        'checkout_time'=>$item->checkout_time,
                        'cancel_time'=>$item->cancel_time,
                        'tax'=>$item->tax,
                        'discount'=>$item->discount,
                        'time_start' => $time->time_start,
                        'time_end' => $time->time_end,
                        'code'=>$item->id,
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
           
            if ($bill_room ->isEmpty())  {
                return response()->json([
                        'message' => 'Data not found!',
                        'status' => 404,
                ]);
            } else {
                $data=[];
                foreach ($bill_room as $item) {
                    $name = DB::table('customers')->where('id', '=', $item->customer_id)->first();
                    $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                    $data[]=[
                        'full_name'=>$name->full_name,
                        'birthday'=>$name->birthday,
                        'phone'=>$name->phone,
                        'total_amount' => $item->total_amount,
                        'total_people' => $item->total_people,
                        'payment_method'=>$item->payment_method,
                        'pay_time'=>$item->pay_time,
                        'checkin_time'=>$item->checkin_time,
                        'checkout_time'=>$item->checkout_time,
                        'cancel_time'=>$item->cancel_time,
                        'tax'=>$item->tax,
                        'discount'=>$item->discount,
                        'time_start' => $time->time_start,
                        'time_end' => $time->time_end,
                        'code'=>$item->id,
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
           
            if ($bill_room ->isEmpty())  {
                return response()->json([
                        'message' => 'Data not found!',
                        'status' => 404,
                ]);
            } else {
                $data=[];
                foreach ($bill_room as $item) {
                    $name = DB::table('customers')->where('id', '=', $item->customer_id)->first();
                    $time = DB::table('reservation_rooms')->where('bill_room_id', '=', $item->id)->first();
                    $data[]=[
                        'full_name'=>$name->full_name,
                        'birthday'=>$name->birthday,
                        'phone'=>$name->phone,
                        'total_amount' => $item->total_amount,
                        'total_people' => $item->total_people,
                        'payment_method'=>$item->payment_method,
                        'pay_time'=>$item->pay_time,
                        'checkin_time'=>$item->checkin_time,
                        'checkout_time'=>$item->checkout_time,
                        'cancel_time'=>$item->cancel_time,
                        'tax'=>$item->tax,
                        'discount'=>$item->discount,
                        'time_start' => $time->time_start,
                        'time_end' => $time->time_end,
                        'code'=>$item->id,
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
            if ($name ->isEmpty())  {
                return response()->json([
                        'message' => 'Data not found!',
                        'status' => 404,
                ]);
            } else {
                $data=[];
                foreach ($name as $item) {
                    $room = DB::table('rooms')->where('id', '=', $item->room_id)->first();
                    $room_type=DB::table('room_types')->where('id', '=', $room->room_type_id)->first();
                    $area=DB::table('areas')->where('id', '=', $room->area_id)->first();
                    $floor=DB::table('floors')->where('id', '=', $room->floor_id)->first();
                    $data[]=[
                        'room_name'=>$room->room_name,
                        'room_type'=>$room_type->room_type_name,
                        'area'=>$area->area_name,
                        'floor' => $floor->floor_name,
                        'price' => $room_type->price,
                        'point_ranking'=>$room_type->point_ranking,
                      
                    ];
                }
            
                return response()->json([
                    'message' => 'Query successfully!',
                    'status' => 200,
                    'bill_room_detail' => $data,
                ]);
            }
    }


}