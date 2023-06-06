<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                    'total_admin' => $number_admin,
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
                    'total_employee' => $number_employee,
                ];
            }
        }

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_department' => $data,
        ], 200);
    }
       public function showAdminByPosition($id)
    {
        $listAdmin =DB::table('admins')->where('position_id', '=', $id)->get();
        if($listAdmin->isEmpty()){
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
        $listEmployee =DB::table('employees')->where('position_id', '=', $id)
        ->where('status', '=', '1')->get();
        if($listEmployee->isEmpty()){
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
}
