<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    public function index($id, $role)
    {
        $data = [];
        if ($role == 0) {
            $list_position = DB::table('positions')->where('department_id', '=', $id)
                ->where('permission', '=', '2')->get();
            foreach ($list_position as $item) {
                $number_admin = DB::table('admins')->where('position_id', '=', $item->id)->count();
                $data[] = [
                    'id' => $item->id,
                    'position_name' => $item->position_name,
                    'total' => $number_admin,
                ];
            }
        }
        if ($role == 1) {
            $list_position = DB::table('positions')->where('department_id', '=', $id)
                ->where('permission', '!=', '2')->get();
            foreach ($list_position as $item) {
                $number_employee = DB::table('employees')->where('position_id', '=', $item->id)
                    ->where('status', '=', '1')->count();
                $data[] = [
                    'id' => $item->id,
                    'position_name' => $item->position_name,
                    'total' => $number_employee,
                ];
            }
        }

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_position' => $data,
        ], 200);
    }
    public function showAdminByPosition($id)
    {
        $listAdmin = DB::table('admins')->where('position_id', '=', $id)->get();
        if ($listAdmin->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ], 404);
        } else {
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'listAdmin' => $listAdmin,
            ], 200);
        }
    }
    public function showEmployeeByPosition($id)
    {
        $listEmployee = DB::table('employees')->where('position_id', '=', $id)
            ->where('status', '=', '1')->get();
        if ($listEmployee->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ], 404);
        } else {
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'listAdmin' => $listEmployee,
            ], 200);
        }
    }
    public function storePosition(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position_name',
            'permission',
            'department_id'
        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $position = Position::create([
            'position_name' => $request->position_name,
            'permission' => $request->permission,
            'department_id' => $request->department_id,
        ]);

        if (!$position) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 400,
            ], 400);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'Position created Successfully',
                'employee' =>  $position,
            ]);
        }
    }
}
