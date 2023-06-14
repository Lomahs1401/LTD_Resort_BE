<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Floor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FloorController extends Controller
{
    public function index()
    {
        $list_floors = DB::table('floors')->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_floors' => $list_floors,
        ], 200);
    }

    public function show($id)
    {
        $floor = DB::table('floors')->where('id', '=', $id)->first();

        if ($floor) {
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'floor' => $floor,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
                'floor' => $floor,
            ], 404);
        }
    }

    public function getTotalFloors()
    {
        $total_floors = DB::table('floors')->count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'total_floors' => $total_floors,
        ], 200);
    }
    
    public function storeFloor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'floor_name',
        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $floor = Floor::create([
            'floor_name' => $request->floor_name,
        ]);

        if (!$floor) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 400,
            ], 400);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'Floor created Successfully',
                'employee' =>  $floor,
            ]);
        }
    }
}
