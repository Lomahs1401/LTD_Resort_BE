<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\service\BillService;
use App\Models\service\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class BillServiceController extends Controller
{
    public function findBillService()
    {
        $bill_service = DB::table('bill_services')
            ->whereNotNull('pay_time')
            ->whereNull('checkin_time')
            ->get();

        if ($bill_service->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ]);
        } else {
            $data = [];
            foreach ($bill_service as $item1) {
                $name = DB::table('customers')->where('id', '=', $item1->customer_id)->first();
                $service = DB::table('services')->where('id', '=', $item1->service_id)->first();
                $service_type = DB::table('service_types')->where('id', '=', $service->service_type_id)->first();
                $data1[] = [
                    'full_name' => $name->full_name,
                    'birthday' => $name->birthday,
                    'phone' => $name->phone,
                    'quantity' => $item1->quantity,
                    'total_amount' => $item1->total_amount,
                    'book_time' => $item1->book_time,
                    'payment_method' => $item1->payment_method,
                    'pay_time' => $item1->pay_time,
                    'tax' => $item1->tax,
                    'discount' => $item1->discount,
                    'service' => $service->service_name,
                    'service_type' => $service_type->service_type_name,
                    'code' => $item1->id,
                ];
            }

            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'bill_room' => $data1,
            ]);
        }
    }
    public function findHistoryService()
    {
        $bill_service = DB::table('bill_services')
            ->whereNotNull('checkin_time')
            ->get();

        if ($bill_service->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ]);
        } else {
            $data = [];
            foreach ($bill_service as $item1) {
                $name = DB::table('customers')->where('id', '=', $item1->customer_id)->first();
                $service = DB::table('services')->where('id', '=', $item1->service_id)->first();
                $service_type = DB::table('service_types')->where('id', '=', $service->service_type_id)->first();
                $data1[] = [
                    'full_name' => $name->full_name,
                    'birthday' => $name->birthday,
                    'phone' => $name->phone,
                    'quantity' => $item1->quantity,
                    'total_amount' => $item1->total_amount,
                    'book_time' => $item1->book_time,
                    'payment_method' => $item1->payment_method,
                    'pay_time' => $item1->pay_time,
                    'tax' => $item1->tax,
                    'discount' => $item1->discount,
                    'service' => $service->service_name,
                    'service_type' => $service_type->service_type_name,
                    'code' => $item1->id,
                ];
            }

            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'bill_room' => $data1,
            ]);
        }
    }
    public function findCancelService()
    {
        $bill_service = DB::table('bill_services')
            ->whereNotNull('checkin_time')
            ->get();

        if ($bill_service->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ]);
        } else {
            $data = [];
            foreach ($bill_service as $item1) {
                $name = DB::table('customers')->where('id', '=', $item1->customer_id)->first();
                $service = DB::table('services')->where('id', '=', $item1->service_id)->first();
                $service_type = DB::table('service_types')->where('id', '=', $service->service_type_id)->first();
                $data1[] = [
                    'full_name' => $name->full_name,
                    'birthday' => $name->birthday,
                    'phone' => $name->phone,
                    'quantity' => $item1->quantity,
                    'total_amount' => $item1->total_amount,
                    'book_time' => $item1->book_time,
                    'payment_method' => $item1->payment_method,
                    'pay_time' => $item1->pay_time,
                    'tax' => $item1->tax,
                    'discount' => $item1->discount,
                    'service' => $service->service_name,
                    'service_type' => $service_type->service_type_name,
                    'code' => $item1->id,
                ];
            }

            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'bill_room' => $data1,
            ]);
        }
    }
    // Kiểm tra nếu 'list_filter_room_types' là null, gán thành một mảng trống
    public function storeBillService(Request $request)
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $validator = Validator::make($request->all(), [
            'quantity' => 'required',
            'book_time' => 'required',
            'service_id' => 'required',

        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }
        $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();
        $ranking = DB::table('rankings')->where('id', '=', $customer->ranking_id)->first();
        if ($customer) {
            $bill_service = BillService::create([
                'quantity' => $request->quantity,
                'total_amount' => $request->total_amount | '0',
                'book_time' => $request->book_time,
                'payment_method' =>  'online',
                'tax' => '0.05',
                'discount' => $ranking->discount,
                'customer_id' => $customer->id,
                'service_id' => $request->service_id,

            ]);

            if ($bill_service) {
                // $service = DB::table('services')->where('id', '=', $bill_service->service_id)->get();
                $service = Service::find($bill_service->service_id);
                $quantity = $bill_service->quantity;
                $discount = $bill_service->discount;
                $tax =$bill_service->tax;
                $total_amount = $service->price * $quantity * (1 -$discount) * (1 + $tax);
                $bill_service->total_amount = $total_amount;
                $bill_service->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'bill Added Successfully',
                    'customer'=> $customer,
                    'bill-service' => $bill_service,
                ]);
            }
        }
    }
    public function deleteBillServiceOverdue()
    {               
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;
              // Lấy danh sách các bill_room cần xóa
              $billServices = BillService::whereNull('pay_time')
              ->whereYear('book_time', '<=', $currentYear)
              ->whereMonth('book_time', '<=', $currentMonth)
              ->whereDay('book_time', '<=', $currentDay)
              ->get();
          foreach ($billServices as $billService) {   
            // Xóa các dữ liệu liên quan (ReservationRooms, ...) trước khi xóa bill_room
          
            // Xóa bill_room
            $billService->delete();
        };
        if ($billServices->isEmpty() ) {
            return response()->json([
                'status' => 404,
                'message' => 'Bill not found',
        
            ]);
           
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'Delete bill service Successfully',
        
            ]);
        }
    }
    public function deleteBillServiceNotPay($id)
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();

            if ($customer) {
            // Xóa các dữ liệu liên quan (ReservationRooms, ...) trước khi xóa bill_room
            $bill_service = DB::table('bill_services')
            ->where('customer_id', '=', $customer->id)
            ->whereNull('pay_time')
            ->where('id', '=', '$id')
            ->delete();
            return response()->json([
                'status' => 200,
                'message' => 'successfully',
                
            ]);
      
        }
    }
}
}
