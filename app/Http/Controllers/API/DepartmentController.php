<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;


class DepartmentController extends Controller
{
    public function index()
    {
        $list_department = Department::all();
        $data = [];
        foreach ($list_department as $item) {
            $number = DB::table('positions')->where('department_id', '=', $item->id)
                ->where('permission', '=', '2')->count();
            $admin = DB::table('positions')->where('department_id', '=', $item->id)
                ->where('permission', '=', '2')->get();
            $total_admin = 0;
            foreach ($admin as $item1) {
                $number_admin = DB::table('admins')->where('position_id', '=', $item1->id)->count();
                $total_admin += $number_admin;
            }
            $number1 = DB::table('positions')->where('department_id', '=', $item->id)
                ->where('permission', '!=', '2')->count();
            $employee = DB::table('positions')->where('department_id', '=', $item->id)
                ->where('permission', '!=', '2')->get();
            $total_employee = 0;
            foreach ($employee as $item2) {
                $number_employee = DB::table('employees')->where('position_id', '=', $item2->id)
                ->where('status', '=', '1')->count();
                $total_employee += $number_employee;
            }
            $data[] = [
                'id' => $item->id,
                'department_name' => $item->department_name,
                'position_admin' => $number,
                'total_admin' => $total_admin,
                'position_employee' => $number1,
                'total_employee' => $total_employee,
            ];
        }
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_department' => $data,
        ], 200);
    }
    public function showByDepartment($id, $role)
    {

        $data = Collection::empty();
        if ($role == 0) {
            $position = DB::table('positions')->where('department_id', '=', $id)
                ->where('permission', '=', '2')->get();
            foreach ($position as $item) {

                $admin = DB::table('admins')->where('position_id', '=', $item->id)->get();
                if ($admin != null) {
                    $data =$data->concat($admin);
                }
              
            }
        }
        if ($role == 1) {
            $position = DB::table('positions')->where('department_id', '=', $id)
                ->where('permission', '!=', '2')->get();
            foreach ($position as $item) {
                $employee = DB::table('employees')->where('position_id', '=', $item->id)
                ->where('status', '=', '1')->get();
                if ($employee != null) {
                    $data=$data->concat($employee);
                }
              
            }
        }
       
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_position' => $data,
        ], 200);
    }
    public function storeDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'department_name',
        
        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }   
        // $employee = Employee::create([
        $department= Department::create([
            'department_name' => $request->department_name,       

        ]);
        // ]);
            // $position = Position::find($data['position_id']);
            if (!$department) {
                return response()->json([
                    'message' => 'Data not found!',
                    'status' => 400,
                ], 400);
            }else {
          return response()->json([
            'status' => 200,
            'message' => 'Department created Successfully',
            'employee' =>  $department,
          

        ]);
    }
    }
}
