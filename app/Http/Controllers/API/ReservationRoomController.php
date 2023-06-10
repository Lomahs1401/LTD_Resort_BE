<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\room\ReservationRoom;
use App\Models\room\RoomType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
class ReservationRoomController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'customer_id' => 'required',
            // 'room_id' => 'required',
            'time_start' => 'required',
            'time_end'=> 'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();

            if ($customer) {
                    $reservation_room = ReservationRoom::create([
                    
                        'time_start' => $request->time_start,
                        'time_end' => $request->time_end,
                        'status'=>'0',
                        'room_id'=> $request->room_id,
                        'customer_id' => $customer->id,
                    ]);

                    if ($reservation_room) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Reservation room created Successfully',
                        'reservation_room' => $reservation_room,
                    ]);
                }else {
                    return response()->json([
                        'message' => 'Data not found!',
                        'status' => 404,
                    ], 404);
                }
            }
        }
    }
    public function delete($id,$time_start,$time_end)
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();

            if ($customer) {
            // Xóa các dữ liệu liên quan (ReservationRooms, ...) trước khi xóa bill_room
            $reservation_room = DB::table('reservation_rooms')
            ->where('customer_id', '=', $customer->id)
            ->where('status', '=', '0')
            ->where('room_id', '=', $id)
            ->where('time_start', '=', $time_start)
            ->where('time_end', '=', $time_end)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'successfully',
                
            ]);
      
        }
    }
}
public function delete30minutes()
{
    // Lấy danh sách các bill_room cần xóa
    $reservation_rooms = ReservationRoom::where('status', '=', '0')
        ->where('created_at', '<=', Carbon::now()->subMinutes(30))
        ->get();
    foreach ($reservation_rooms as $reservation_room) {
        $reservation_room->delete();
    };
    if ($reservation_rooms->isEmpty()) {
        return response()->json([
            'status' => 404,
            'message' => 'Reservation room not found',

        ]);
    } else {
        return response()->json([
            'status' => 200,
            'message' => 'Delete reservation room Successfully',

        ]);
    }
}
public function checkCount($time_start,$time_end)
{
    $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();

            if ($customer) {
            // Xóa các dữ liệu liên quan (ReservationRooms, ...) trước khi xóa bill_room
            $count = DB::table('reservation_rooms')
            ->where('customer_id', '=', $customer->id)
            ->where('status', '=', '0')
            ->where('time_start', '=', $time_start)
            ->where('time_end', '=', $time_end)->count();
            return response()->json([
                'status' => 200,
                'message' => 'successfully',
                'count' =>$count,
            ]);
      
        }
    }
}
public function CheckIfOtherCustomersHavePaid($time_start,$time_end)
{
    $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();

            if ($customer) {
            // Xóa các dữ liệu liên quan (ReservationRooms, ...) trước khi xóa bill_room
            $reservation_rooms = DB::table('reservation_rooms')
            ->where('customer_id', '=', $customer->id)
            ->where('status', '=', '0')
            ->where('time_start', '=', $time_start)
            ->where('time_end', '=', $time_end)->get();
        $reservedRooms1 = ReservationRoom::where('status', '=', '1')
            ->whereDate('time_start', '>=',  $time_start)
            ->whereDate('time_end', '<=', $time_end)
            ->get();
        $reservedRooms2 = ReservationRoom::where('status', '=', '1')
            ->whereDate('time_start', '<',  $time_start)
            ->whereDate('time_end', '>', $time_start)
            ->get();
        $reservedRooms3 = ReservationRoom::where('status', '=', '1')
            ->whereDate('time_start', '<',  $time_end)
            ->whereDate('time_end', '>', $time_end)
            ->get();
        $reservedRooms4 = ReservationRoom::where('status', '=', '1')
            ->whereDate('time_start', '<',  $time_start)
            ->whereDate('time_end', '>', $time_end)
            ->get();
        $reservedRooms = Collection::empty();
        $reservedRooms = $reservedRooms->concat($reservedRooms1);
        $reservedRooms = $reservedRooms->concat($reservedRooms2);
        $reservedRooms = $reservedRooms->concat($reservedRooms3);
        $reservedRooms = $reservedRooms->concat($reservedRooms4);
      
        $reservedRooms = collect($reservedRooms)
            ->reject(function ($item) {
                return empty($item);
            })
            ->unique()
            ->values()
            ->all();
            $count = 0;
            $data = [];
            foreach ($reservation_rooms as $reservation_room) {
                foreach ($reservedRooms as $reservedRoom) {
                    if($reservation_room->room_id == $reservedRoom->room_id){
                        $count += 1;
                        $room = DB::table('rooms')->where('id', '=', $reservedRoom->room_id)->get();
                        $data []= $room;
                    }

         
      
        }
    }
    return response()->json([
        'status' => 200,
        'message' => 'successfully',
        'count' =>$count,
        'data'=>$data,
        'reservation_rooms'=>$reservation_rooms,
        'reservedRooms'=>$reservedRooms,

    ]);
 }
}
}
public function ShowBillNotPayByCustomer($time_start,$time_end)
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
            // Xóa các dữ liệu liên quan (ReservationRooms, ...) trước khi xóa bill_room
            $reservation_rooms = DB::table('reservation_rooms')
            ->where('customer_id', '=', $customer->id)
            ->where('status', '=', '0')
            ->where('time_start', '=', $time_start)
            ->where('time_end', '=', $time_end)->get();
            $data = [];
            $room_type = RoomType::all();
            foreach ( $room_type as $item) {
                $data1 = [];
            foreach ($reservation_rooms as $reservation_room) {
               $room = DB::table('rooms')->where('room_type_id','=',$item->id)
               ->where('id','=',$reservation_room->room_id)->first();
               if($room != null){
                $data1[] = $room;
               }
            }
            $feedback1 = DB::table('feedback')->where('room_type_id', '=', $item->room_type_id)->sum('rating');
            $total_feedback1=DB::table('feedback')->where('room_type_id', '=', $item->room_type_id)->count();
            $average_rating_room_type = $total_feedback1 > 0 ? $feedback1 / $total_feedback1 : 0;
            $data[]=[
                'id'=>$item->id,
                'room_type_name'=>$item->room_type_name,
                'room_size'=>$item->room_size,
                'number_customers'=>$item->number_customers,
                'description'=>$item->description,
                'image'=>$item->image,
                'price'=>$item->price,
                'point_ranking'=>$item->point_ranking,
                'description'=>$item->description,
                'total_feedback'=>$total_feedback1,
                'average_rating_room_type'=>$average_rating_room_type,
                'room'=> $data1,
            ];
            }
            $filteredData = [];
            foreach ($data as $item) {
                if (!empty($item['room'])) {
                    $filteredData[] = $item;
                }
            }
            $bill_service =DB::table('bill_services')
            ->where('customer_id', '=', $customer->id)
            ->whereNull('pay_time')->get();
            $bill_service_count =DB::table('bill_services')
            ->where('customer_id', '=', $customer->id)
            ->whereNull('pay_time')->count();
            $data  = [];

            foreach ($bill_service as $item) {
                $total_people += $item->quantity;
                $total_amount += $item->total_amount;
                $service = DB::table('services')->where('id', '=', $item->service_id)->first();
                $feedback = DB::table('feedback')->where('service_id', '=', $item->service_id)->sum('rating');
                $total_feedback=DB::table('feedback')->where('service_id', '=', $item->service_id)->count();
                $average_rating_service = $total_feedback > 0 ? $feedback / $total_feedback : 0;
                $service_type = DB::table('service_types')->where('id', '=', $service->service_type_id)->first();
                $data[] = [
                    'id_bill'=>$item->id,
                    'quantity' => $item->quantity,
                    'total_amount' => $item->total_amount,
                    'book_time' => $item->book_time,
                    'payment_method' => $item->payment_method,
                    'pay_time' => $item->pay_time,
                    'tax' => $item->tax,
                    'discount' => $item->discount,
                    'service_id' => $service->id,
                    'service_name' => $service->service_name,
                    'description' => $service->description,
                    'image' => $service->image,
                    'status' => $service->status,
                    'price' => $service->price,
                    'point_ranking' => $service->point_ranking,
                    'service_type_id' => $service_type->id,
                    'service_type' => $service_type->service_type_name,
                    'total_feedback'=>$total_feedback,
                    'average_rating_service'=>$average_rating_service
                   
                ];
            }
                $total_room = DB::table('reservation_rooms')
                ->where('customer_id', '=', $customer->id)
                ->where('status', '=', '0')
                ->where('time_start', '=', $time_start)
                ->where('time_end', '=', $time_end)->count();
               
                $price = 0;
                $reservation_rooms = DB::table('reservation_rooms')
                ->where('customer_id', '=', $customer->id)
                ->where('status', '=', '0')
                ->where('time_start', '=', $time_start)
                ->where('time_end', '=', $time_end)->get();
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
                $total_amount += $numberOfDays * $price* (1 - $ranking->discount) * (1 + 0.05);
                // $total_money = $total_amount* (1 - $ranking->discount) * (1 + 0.05);
    return response()->json([
        'status' => 200,
        'message' => 'successfully',
        'reservation_rooms'=>$filteredData,
        'service_bill_pay'=> $data,
        'numberOfDays'=>$numberOfDays,
        'bill_service_count'=>$bill_service_count,
        'total_room'=>$total_room,
        'total_people'=>$total_people,
        'discount'=>$ranking->discount,
        'tax'=> '0.05',
        'total_money'=>$total_amount


        

    ]);
 }
}
}
}
