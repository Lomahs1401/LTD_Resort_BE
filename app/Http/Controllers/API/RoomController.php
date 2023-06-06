<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\room\ReservationRoom;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
class RoomController extends Controller
{
    public function index() {
        $list_rooms = DB::table('rooms')->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_rooms' => $list_rooms,
        ], 200);
    }

    public function show($id) {
        $room = DB::table('rooms')->where('id', '=', $id)->first();

        if ($room) {
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'room' => $room,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
                'room' => $room,
            ], 404);
        }
    }

    public function getRoomsByRoomTypeId($id) {
        $list_rooms = DB::table('rooms')->where('room_type_id', '=', $id)->get();

        return response()->json([
            'status' => 200,
            'list_rooms' => $list_rooms,
        ]);
    }
    public function getReservedRooms(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'time_start',
            'time_end',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validate' => true,
                'message' => 'you need to enter enough',
            ]);
        }
        $timeStart = $request->time_start;
        $timeEnd = $request->time_end;
        $reservedRooms1 =ReservationRoom::whereDate('time_start', '>=',  $timeStart)
        ->whereDate('time_end', '<=', $timeEnd)
        ->get();
        $reservedRooms2 =ReservationRoom::whereDate('time_start', '<',  $timeStart)
        ->whereDate('time_end', '>', $timeStart)
        ->get();
        $reservedRooms3 =ReservationRoom::whereDate('time_start', '<',  $timeEnd)
        ->whereDate('time_end', '>', $timeEnd)
        ->get();
        $reservedRooms4 =ReservationRoom::whereDate('time_start', '<',  $timeStart)
        ->whereDate('time_end', '>', $timeEnd)
        ->get();
        $reservedRooms = Collection::empty();
        $reservedRooms = $reservedRooms->concat($reservedRooms1);
        $reservedRooms = $reservedRooms->concat($reservedRooms2);
        $reservedRooms = $reservedRooms->concat($reservedRooms3);
        $reservedRooms = $reservedRooms->concat($reservedRooms4);
        $data = [];
                foreach ($reservedRooms as $item) {
                    $number = DB::table('rooms')->where('id', '=', $item->room_id)
                    ->where('room_type_id', '=', $id)->first();
                    if ($number !== null) {
                        $data[] = $number;
                    }
                }
                $data = collect($data)
                ->reject(function ($item) {
                    return empty($item);
                })
                ->unique()
                ->values()
                ->all();
                $data1 = [];
                foreach ($data as $item1) {
                $list_area = DB::table('areas')->get();
                foreach ($list_area as $item2) {
                    $area = $item2->id;
                    $data1[$area] = [];
                    $list_floor = DB::table('floors')->get();
                    foreach ($list_floor as $item3) {
                        $floor = $item3->id;
                        $list_rooms = DB::table('rooms')
                        ->where('id', '=', $item1->id)
                        ->where('area_id', '=',$area)
                        ->where('floor_id', '=',$floor)
                        ->get();
                        $data1[$area][$floor] = [];
                        $data1[$area][$floor] = $list_rooms;
                }
                $data1 = array_values($data1);
            }
        }
        return response()->json([
        'reserved_rooms' => $reservedRooms,
        'data'=>$data1
        
]);
}

}
