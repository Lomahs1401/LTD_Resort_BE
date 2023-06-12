<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\room\ExtraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class BillExtraServiceController extends Controller
{
        public function index()
    {
        $extra_service = ExtraService::all();

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $extra_service,
        ]);
    }
    public function show($id)
{
    $extra_service = ExtraService::find($id);

    if (!$extra_service) {
        return response()->json([
            'status' => 404,
            'message' => 'Manager not found',
        ], 404);
    }

    return response()->json([
        'status' => 200,
        'message' => 'Success',
        'data' => $extra_service,
    ]);
}
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'extra_service_name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }
      

        // $employee = Employee::create([
        $extra_service=ExtraService::create([
       
            'extra_service_name' => $request->extra_service_name,
            'description' => $request->required,
            'price' => $request->price,
          
        ]);
       
       
            if ($extra_service) {
               
        return response()->json([
            'status' => 200,
            'message' => 'Etra Service created Successfully',
            'extra_service' => $extra_service,
        ]);
    }
}
public function update(Request $request,$id)
{
   
    $data = ExtraService::find($id);
    
    if($data ){
      
        if ($request->extra_service_name) {
            $data->address = $request->extra_service_name;
        }
        if ($request->description) {
            $data->description = $request->description;
        }
        if ($request->price) {
            $data->price = $request->account_pricebank;
        }
        if ($request->image) {
            $data->image = $request->image;
        }

        $data->update();
        return response()->json([
            'message' => 'Update successfully!',
            'status' => 200,
            'data' => $data,
           
        ], 200);
    }else{
        return response()->json([
            'message' => 'Data not found!',
            'status' => 401,
        ], 401);
    }

}
    public function findBillExtraDetail($id)
    {
        $name = DB::table('bill_extra_service_details')->where('bill_extra_service_id', '=', $id)->get();
        if ($name->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ]);
        } else {
            $data = [];
            foreach ($name as $item) {
                $extra_service = DB::table('extra_services')->where('id', '=', $item->extra_service_id)->first();
                $data[] = [
                    'extra_service_name' => $extra_service->extra_service_name,
                    'price' => $extra_service->price,
                    'quantity' => $item->quantity,
                    'amount' => $item->amount,
              

                ];
            }

            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'bill_extra_service_details' => $data,
            ]);
        }
    }
}
