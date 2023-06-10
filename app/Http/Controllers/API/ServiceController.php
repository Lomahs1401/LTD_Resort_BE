<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\service\Service;
use App\Models\service\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class ServiceController extends Controller
{
    public function indexService() {
        $list_services = Service::all();
        $data = [];
                foreach ($list_services as $item) {
                    $number = ServiceType::find($item->service_type_id);
                    $data[] = [
                        'id' => $item->id,
                        'service_name' => $item->service_name,
                        'description' => $item->description,
                        'image' => $item->image,
                        'status' => $item->status,
                        'price' => $item->price,
                        'point_ranking' => $item->point_ranking,
                        'service_type_name' => $number->service_type_name,
                         
                    ];
                }
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_service_types' => $data,
        ], 200);
    }

    public function showService($id) {
        $item = Service::find($id);
        if ($item) {
            $number = ServiceType::find($item->service_type_id);
            $data[] = [
                'id' => $item->id,
                'service_name' => $item->service_name,
                'description' => $item->description,
                'image' => $item->image,
                'status' => $item->status,
                'price' => $item->price,
                'point_ranking' => $item->point_ranking,
                'service_type_name' => $number->service_type_name,
                 
            ];
          
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'service' => $item,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
                'service' => $item,
            ], 404);
        }
    }
    public function indexServiceType()
    {
        $list_room_types = ServiceType::all();

        $data = [];
                foreach ($list_room_types as $item) {
                    $number = DB::table('services')->where('service_type_id', '=', $item->id)
                    ->where('status', '=', 'AVAILABLE')->count();
                    $data[] = [
                        'id' => $item->id,
                        'service_type_name' => $item->service_type_name,
                        'number_services' => $number,
                             
                    ];
                }
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_room_types' => $data,
        ], 200);
    }
    public function showServiceByServiceType($id) {
        $number = DB::table('services')->where('service_type_id', '=', $id)
        ->where('status', '=', 'AVAILABLE')->get();
        $data = [];
                foreach ($number as $item) {
                    $number1 = ServiceType::find($item->service_type_id);
                    $data[] = [
                        'id' => $item->id,
                        'service_name' => $item->service_name,
                        'description' => $item->description,
                        'image' => $item->image,
                        'status' => $item->status,
                        'price' => $item->price,
                        'point_ranking' => $item->point_ranking,
                        'service_type_name' => $number1->service_type_name,
                         
                    ];
                }
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_room_types' => $data,
        ], 200);
    }
    public function updateService(Request $request, $id)
    {
        $service= Service::find($id);
        if ($service) {
        
            if ($request->service_name) {
                $service->service_name = $request->service_name;
            }
            if ($request->description) {
                $service->description = $request->description;
            }
            if ($request->price) {
                $service->price = $request->price;
            }
            if ($request->image) {
                $service->image = $request->image;
            }
            if ($request->point_ranking) {
                $service->point_ranking = $request->point_ranking;
            }
            $service->update();
            return response()->json([
                'message' => 'Update successfully!',
                'status' => 200,
                'room_type' => $service,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ], 404);
        }
    }
    public function storeService(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'service_name',
        'description',
        'image',
        'price',
        'point_ranking',
        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }   
        // $employee = Employee::create([
        $service= Service::create([
            'service_name' => $request->service_name,
            'description' => $request->description,
            'status' => 'AVAILABLE',           
            'image' =>  $request->image,
            'price' => $request->price,
            'point_ranking' =>$request->point_ranking,
            'service_type_id' => $request->service_type_id,

        ]);
        // ]);
            // $position = Position::find($data['position_id']);
            if (!$service) {
                return response()->json([
                    'message' => 'Data not found!',
                    'status' => 400,
                ], 400);
            }else {
          return response()->json([
            'status' => 200,
            'message' => 'Room Type created Successfully',
            'employee' => $service,
          

        ]);
    }
    }
    public function storeServiceType(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'service_type_name',
      
        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }   
        // $employee = Employee::create([
        $service= ServiceType::create([
            'service_type_name' => $request->service_name,

        ]);
        // ]);
            // $position = Position::find($data['position_id']);
            if (!$service) {
                return response()->json([
                    'message' => 'Data not found!',
                    'status' => 400,
                ], 400);
            }else {
          return response()->json([
            'status' => 200,
            'message' => 'Room Type created Successfully',
            'employee' => $service,
        ]);
    }
    }
    public function cancelService( $id)
    {
        $service= Service::find($id);
        if ($service) { 
                $service->status = 'UNAVAILABLE';
                $service->update();
            return response()->json([
                'message' => 'Cancel successfully!',
                'status' => 200,
                'room_type' => $service,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ], 404);
        }
    } 
    public function show($id) {
        $service = Service::find($id);
        if ($service) {
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'service' => $service,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
                'service' => $service,
            ], 404);
        }
    }
    public function filterService(Request $request) {
        $price = $request->price;
        $services = $request->input('services');

        $list_filter_services = [];

        if ($price != 0) {
            if (count($services) != 0) {
                $list_filter_services = DB::table('services')
                    ->where('price', '<=', $price)
                    ->where(function ($query) use ($services) {
                        foreach ($services as $service) {
                            $query->orWhere('service_name', 'LIKE', '%' . $service . '%');
                        }
                    })
                    ->get();
            } else {
                $list_filter_services = DB::table('services')
                    ->where('price', '<=', $price)
                    ->get();
            }
        } else {
            if (count($services) != 0) {
                $list_filter_services = DB::table('services')
                    ->where(function ($query) use ($services) {
                        foreach ($services as $service) {
                            $query->orWhere('service_name', 'LIKE', '%' . $service . '%');
                        }
                    })
                    ->get();
            } else {
                $list_filter_services = DB::table('services')->get();
            }
        }

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_filter_services' => $list_filter_services,
        ], 200);
    }

    public function getTotalServices() {
        $total_services = DB::table('services')->count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'total_services' => $total_services,
        ], 200);
    }

    public function getLowestPrice()
    {
        $lowest_price = DB::table('services')->min('price');

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'lowest_price' => $lowest_price,
        ], 200);
    }

    public function getHighestPrice()
    {
        $highest_price = DB::table('services')->max('price');

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'highest_price' => $highest_price,
        ], 200);
    }

    public function getListServiceNames() {
        $list_service_names = DB::table('services')->get(['service_name']);

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_service_names' => $list_service_names,
        ], 200);
    }

    public function getTop5LowestPrice()
    {
        $list_lowest_price = DB::table('services')->orderBy('price', 'asc')->take(5)->get();

        $list_lowest_price = DB::table('services')
            ->join('feedback', 'services.id', '=', 'feedback.service_id')
            ->select('services.id', 'services.image', 'services.service_name', 'services.price', DB::raw('AVG(feedback.rating) as average_rating'))
            ->groupBy('services.id', 'services.image', 'services.service_name', 'services.price')
            ->orderBy('services.price', 'asc')
            ->take(5)
            ->get();

        
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_lowest_price' => $list_lowest_price
        ]);
    }

    public function getRandomServices($id) 
    {
        $list_random_services = DB::table('services')
            ->join('feedback', 'services.id', '=', 'feedback.service_id')
            ->select('services.id', 'services.image', 'services.service_name', 'services.price', DB::raw('AVG(feedback.rating) as average_rating'))
            ->groupBy('services.id', 'services.image', 'services.service_name', 'services.price')
            ->orderBy('services.price', 'asc')
            ->whereNotIn('services.id', [$id])->inRandomOrder()
            ->take(5)
            ->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_random_services' => $list_random_services
        ]);
    }

    public function paging(Request $request, $page_number, $num_of_page)
    {
        $data = [];

        $list_filter_services = $request->input('list_filter_services');

        // Kiểm tra nếu 'list_filter_services' là null, gán thành một mảng trống
        if ($list_filter_services === null) {
            $list_filter_services = [];
        }

        $average_ratings = DB::table('feedback')
            ->select('service_id', DB::raw('AVG(rating) as average_rating'))
            ->groupBy('service_id')
            ->get();

        if (count($list_filter_services) > 0) {
            if (count($list_filter_services) % $num_of_page == 0) {
                $page = count($list_filter_services) / $num_of_page;
            } else {
                $page = (int)(count($list_filter_services) / $num_of_page) + 1;
            }

            $start = ($page_number - 1) * $num_of_page;

            if ($page_number == $page) {
                for ($i = $start; $i < count($list_filter_services); $i++) {
                    $data[] = [
                        "id" => $list_filter_services[$i]['id'],
                        "service_name" => $list_filter_services[$i]['service_name'],
                        "image" => $list_filter_services[$i]['image'],
                        "description" => $list_filter_services[$i]['description'],
                        "status" => $list_filter_services[$i]['status'],
                        "price" => $list_filter_services[$i]['price'],
                        "point_ranking" => $list_filter_services[$i]['point_ranking'],
                        "service_type_id" => $list_filter_services[$i]['service_type_id'],
                        "rating" => $this->getAverageRating($list_filter_services[$i]['id'], $average_ratings),
                        "created_at" => $list_filter_services[$i]['created_at'],
                        "updated_at" => $list_filter_services[$i]['updated_at'],
                    ];
                }
            } else if ($page_number > $page) {
                $data = [];
            } else {
                for ($i = $start; $i < $start + $num_of_page; $i++) {
                    $data[] = [
                        "id" => $list_filter_services[$i]['id'],
                        "service_name" => $list_filter_services[$i]['service_name'],
                        "image" => $list_filter_services[$i]['image'],
                        "description" => $list_filter_services[$i]['description'],
                        "status" => $list_filter_services[$i]['status'],
                        "price" => $list_filter_services[$i]['price'],
                        "point_ranking" => $list_filter_services[$i]['point_ranking'],
                        "service_type_id" => $list_filter_services[$i]['service_type_id'],
                        "rating" => $this->getAverageRating($list_filter_services[$i]['id'], $average_ratings),
                        "created_at" => $list_filter_services[$i]['created_at'],
                        "updated_at" => $list_filter_services[$i]['updated_at'],
                    ];
                }
            }
        } else {
            $list_services = DB::table('services')->get();
            if (count($list_services) > 0) {
                if (count($list_services) % $num_of_page == 0) {
                    $page = count($list_services) / $num_of_page;
                } else {
                    $page = (int)(count($list_services) / $num_of_page) + 1;
                }
    
                $start = ($page_number - 1) * $num_of_page;
                $data = [];
    
                if ($page_number == $page) {
                    for ($i = $start; $i < count($list_services); $i++) {
                        $data[] = [
                            "id" => $list_services[$i]->id,
                            "service_name" => $list_services[$i]->service_name,
                            "image" => $list_services[$i]->image,
                            "description" => $list_services[$i]->description,
                            "status" => $list_services[$i]->status,
                            "price" => $list_services[$i]->price,
                            "point_ranking" => $list_services[$i]->point_ranking,
                            "service_type_id" => $list_services[$i]->service_type_id,
                            "rating" => $this->getAverageRating($list_services[$i]->id, $average_ratings),
                            "created_at" => $list_services[$i]->created_at,
                            "updated_at" => $list_services[$i]->updated_at,
                        ];
                    }
                } else if ($page_number > $page) {
                    $data = [];
                } else {
                    for ($i = $start; $i < $start + $num_of_page; $i++) {
                        $data[] = [
                            "id" => $list_services[$i]->id,
                            "service_name" => $list_services[$i]->service_name,
                            "image" => $list_services[$i]->image,
                            "description" => $list_services[$i]->description,
                            "status" => $list_services[$i]->status,
                            "price" => $list_services[$i]->price,
                            "point_ranking" => $list_services[$i]->point_ranking,
                            "service_type_id" => $list_services[$i]->service_type_id,
                            "rating" => $this->getAverageRating($list_services[$i]->id, $average_ratings),
                            "created_at" => $list_services[$i]->created_at,
                            "updated_at" => $list_services[$i]->updated_at,
                        ];
                    }
                }
            }
        }

        return response()->json([
            'status' => 200,
            'list_services' => array_values($data), // Sử dụng array_values() để chuyển đổi dạng object thành mảng,
        ]);
    }

    public function getAverageRating($serviceId, $averageRatings)
    {
        foreach ($averageRatings as $rating) {
            if ($rating->service_id == $serviceId) {
                return $rating->average_rating;
            }
        }

        return null;
    }
}

