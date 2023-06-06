<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function getReservedRooms(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'time_start',
            'time_end',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validate' => true,
                'message' => 'You need to enter employee',
            ]);
        }
        $timeStart = $request->time_start;
        $timeEnd = $request->time_end;

        $reservedRooms =DB::table('reservation_rooms')->where('time_start', '>=', $timeStart)
            ->where('time_end', '<=', $timeEnd)
            ->pluck('room_id')
            ->toArray();

        return response()->json(['reserved_rooms' => $reservedRooms]);
    }

}
