<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    function total()
    {
        $total_customer = DB::table('customers')->count();
        $total_employee = DB::table('employees')->where('status', '=', '1')->count();
        $total_feedback = DB::table('feedback')->count();
        $total_money = 0;
        // Tính tổng total_amount của bảng bill_room
        $billRooms = DB::table('bill_rooms')
            ->whereNotNull('pay_time')
            ->get();
        $total_money += $billRooms->sum('total_amount');

        // Tính tổng total_amount của bảng bill_service
        $billServices = DB::table('bill_services')
            ->whereNotNull('pay_time')
            ->get();
        $total_money += $billServices->sum('total_amount');

        // Tính tổng total_amount của bảng bill_extra_service
        $billExtraServices = DB::table('bill_extra_services')->get();
        $total_money += $billExtraServices->sum('total_amount');

        return response()->json([
            'status' => 200,
            'message' => ' Successfully',
            'total_customer' => $total_customer,
            'total_employee' => $total_employee,
            'total_feedback' => $total_feedback,
            'total_money' => $total_money,
        ]);
    }
    function totalBill($currentYear)
    {
        $months = range(1, 12);
        $monthNames = array_map(function ($month) {
            return date('F', mktime(0, 0, 0, $month, 1));
        }, $months);
        $data = [];
        $data1 = [];
        $data2 = [];
        $i = 1;
        foreach ($monthNames as $item1) {

            $billRooms = DB::table('bill_rooms')
                ->whereYear('pay_time', $currentYear)
                ->whereMonth('pay_time', $i)
                ->get();
            $total_money_bill_room = $billRooms->sum('total_amount');
            $data[] = [
                'month' => $item1,
                'total' => $total_money_bill_room,

            ];
            $billServices = DB::table('bill_services')
                ->whereYear('pay_time', $currentYear)
                ->whereMonth('pay_time', $i)
                ->get();
            $total_money_bill_service = $billServices->sum('total_amount');
            $data1[] = [
                'month' => $item1,
                'total' => $total_money_bill_service
            ];
            $billExtraServices = DB::table('bill_extra_services')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->get();
            $total_money_bill_extra_service = $billExtraServices->sum('total_amount');
            $data2[] = [
                'month' => $item1,
                'total' => $total_money_bill_extra_service
            ];
            $i += 1;
        }
        return response()->json([
            'status' => 200,
            'message' => 'Successfully',
            'year' => $currentYear,
            'total_money_bill_room' => $data,
            'total_money_bill_service' => $data1,
            'total_money_bill_extra_service' => $data2,
        ]);
    }

    function totalBillMonth()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $billRooms = DB::table('bill_rooms')
            ->whereYear('pay_time', $currentYear)
            ->whereMonth('pay_time', $currentMonth)
            ->get();
        $total_room = $billRooms->sum('total_room');
        $billServices = DB::table('bill_services')
            ->whereYear('pay_time', $currentYear)
            ->whereMonth('pay_time', $currentMonth)
            ->get();
        $quantity_service = $billServices->sum('quantity');

        $billExtraServices = DB::table('bill_extra_services')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get();
        $quantity_extra_service = 0;
        foreach ($billExtraServices as $item1) {
            $billExtraDetail = DB::table('bill_extra_service_details')
                ->where('bill_extra_service_id', '=', $item1->id)
                ->get();
            $quantity_extra_service += $billExtraDetail->sum('quantity');
         
        }
        $data1 = [
            "id" => 'Total Room',
            "name" => 'Total Room',
            "total" => $total_room
        ];
        $data2 = [
            "id" => 'Total Service',
            "name" => 'Total Service',
            "total" => $quantity_service
        ];
        $data3 = [
            "id" => 'Total Extral Service',
            "name" => 'Total Extral Service',
            "total" => $quantity_extra_service
        ];
        $data[] = [$data3, $data2, $data1];
        return response()->json([
            'status' => 200,
            'message' => 'Successfully',
            'data' =>  $data,
        ]);
    }
    function totalFeedback($currentYear)
    {
        $months = range(1, 12);
        $monthNames = array_map(function ($month) {
            return date('F', mktime(0, 0, 0, $month, 1));
        }, $months);
        $data = [];
        $i = 1;
        foreach ($monthNames as $item1) {

            $feedback5 = DB::table('feedback')
                ->whereYear('date_request', $currentYear)
                ->whereMonth('date_request', $i)
                ->where('rating', '=', '5')
                ->count();
            $feedback4 = DB::table('feedback')
                ->whereYear('date_request', $currentYear)
                ->whereMonth('date_request', $i)
                ->where('rating', '=', '4')
                ->count();
            $feedback3 = DB::table('feedback')
                ->whereYear('date_request', $currentYear)
                ->whereMonth('date_request', $i)
                ->where('rating', '=', '3')
                ->count();
            $feedback2 = DB::table('feedback')
                ->whereYear('date_request', $currentYear)
                ->whereMonth('date_request', $i)
                ->where('rating', '=', '2')
                ->count();
            $feedback1 = DB::table('feedback')
                ->whereYear('date_request', $currentYear)
                ->whereMonth('date_request', $i)
                ->where('rating', '=', '1')
                ->count();
            $data[] = [
                'month' => $item1,
                'Feedback 5' => $feedback5,
                'Feedback 4' => $feedback4,
                'Feedback 3' => $feedback3,
                'Feedback 2' => $feedback2,
                'Feedback 1' => $feedback1,
            ];
            $i += 1;
        }
        return response()->json([
            'status' => 200,
            'message' => 'Successfully',
            'year' => $currentYear,
            'Total_feedback' => $data,
        ]);
    }
    function totalEmployeeMonth()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $totalEmployee = DB::table('employees')
            ->where('status', '=', '1')
            ->count();
        $totalEmployeeNew = DB::table('employees')
            ->where('status', '=', '1')
            ->whereYear('day_start', $currentYear)
            ->whereMonth('day_start', $currentMonth)
            ->count();
        $totalEmployeeCancel = DB::table('employees')
            ->where('status', '=', '0')
            ->whereYear('day_quit', $currentYear)
            ->whereMonth('day_quit', $currentMonth)
            ->count();
        $totalEmployeeOld =  $totalEmployee - $totalEmployeeNew;
        $data1 = [
            "id" => 'Total Employee Old',
            "name" => 'Total Employee Old',
            "total" => $totalEmployeeOld
        ];
        $data2 = [
            "id" => 'Total Employee New',
            "name" => 'Total Employee New',
            "total" => $totalEmployeeNew
        ];
        $data3 = [
            "id" => 'Total Employee Cancel',
            "name" => 'Total Employee Cancel',
            "total" => $totalEmployeeCancel
        ];
        $data[] = [$data3, $data2, $data1];


        return response()->json([
            'status' => 200,
            'message' => 'Successfully',
            'data' =>   $data
        ]);
    }
}
