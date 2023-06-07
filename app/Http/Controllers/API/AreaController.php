<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\room\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class AreaController extends Controller
{
    public function index()
    {
        $list_areas = DB::table('areas')->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_areas' => $list_areas,
        ], 200);
    }

    public function show($id)
    {
        $area = DB::table('areas')->where('id', '=', $id)->first();

        if ($area) {
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'area' => $area,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
                'area' => $area,
            ], 404);
        }
    }

    public function getTotalAreas() {
        $total_areas = DB::table('areas')->count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'total_areas' => $total_areas,
        ], 200);
    }

     public function storeArea(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'area_name',
        
        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }   
        // $employee = Employee::create([
        $area= Area::create([
            'area_name' => $request->area_name,       

        ]);
        // ]);
            // $position = Position::find($data['position_id']);
            if (!$area) {
                return response()->json([
                    'message' => 'Data not found!',
                    'status' => 400,
                ], 400);
            }else {
          return response()->json([
            'status' => 200,
            'message' => 'Area created Successfully',
            'employee' =>  $area,
          

        ]);
    }
    }
}
