<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillExtraServiceController extends Controller
{
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
