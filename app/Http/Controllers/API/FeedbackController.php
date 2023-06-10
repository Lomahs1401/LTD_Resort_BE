<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\Position;
use App\Models\room\RoomType;
use App\Models\service\Service;
use App\Models\user\Customer;
use App\Models\user\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FeedbackController extends Controller
{
    public function indexNotFeedback()
    {
        $list_feedback_room = DB::table('feedback')
            ->where('feedback_status', '=', 'Not feedbacked yet')
            ->where('feedback_type', '=', 'ROOM')->get();
        $list_feedback_service = DB::table('feedback')
            ->where('feedback_status', '=', 'Not feedbacked yet')
            ->where('feedback_type', '=', 'SERVICE')->get();
        $data = [];
        $data1 = [];
        foreach ($list_feedback_room as $item) {
            $customer = Customer::find($item->customer_id);
            $room_type = RoomType::find($item->room_type_id);
            $data[] = [
                'id' => $item->id,
                'customer_name' => $customer->full_name,
                'room_type_name' => $room_type->room_type_name,
                'rating' => $item->rating,
                'title' => $item->title,
                'comment' => $item->comment,
                'date_request' => $item->date_request,
                'image' => $item->image,
            ];
        }
        foreach ($list_feedback_service as $item1) {
            $customer = Customer::find($item1->customer_id);
            $service = Service::find($item1->service_id);
            $data1 [] = [
                'id' => $item1->id,
                'customer_name' => $customer->full_name,
                'service_name' => $service->service_name,
                'rating' => $item1->rating,
                'title' => $item1->title,
                'comment' => $item1->comment,
                'date_request' => $item1->date_request,
                'image' => $item1->image,
            ];
        }
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_feedback_room' => $data,
            'list_feedback_service' => $data1,
        ], 200);
    }

    public function show($id)
    {
        $feedback = DB::table('feedback')->find($id);
        if ($feedback) {
            $customer = Customer::find($feedback->customer_id);
            $service = Service::find($feedback->service_id);
            $room_type = RoomType::find($feedback->room_type_id);
            $employee = Employee::find($feedback->employee_id);
            $position = null;
            if ($employee && isset($employee->position_id)) {
                $position = Position::find($employee->position_id);
            }
            $department = null;
            if ($position && isset($position->department_id)) {
                $department = Department::find($position->department_id);
            }
            $data  = [
                'id' => $feedback->id,
                'customer_name' => $customer->full_name,
                'service_name' => $service ? $service->service_name : null,//hiển thị trong feedback service //1
                'room_type_name' => $room_type ? $room_type->room_type_name : null,//hiển thị feedback trong room  //1
                'employee_name' => $employee ? $employee->full_name : null,//hiển thị trong Admin //3
                'position' => $position ? $position->position_name : null,//hiển thị trong Admin //3
                'department' => $department ? $department->department_name : null,//hiển thị trong Admin  //3                
                'rating' => $feedback->rating,
                'title' => $feedback->title,
                'comment' => $feedback->comment,
                'date_request' => $feedback->date_request,
                'date_response' => $feedback->date_response, // hiển thị trong danh sách feeback //2
                'image' => $feedback->image,
            ];
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'feedback' =>$data,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
            ], 404);
        }
    }
    public function indexFeedbackAdmin()
    {
        $list_feedback_room = DB::table('feedback')
            ->where('feedback_status', '=', 'Feedbacked')
            ->where('feedback_type', '=', 'ROOM')->get();
        $list_feedback_service = DB::table('feedback')
            ->where('feedback_status', '=', 'Feedbacked')
            ->where('feedback_type', '=', 'SERVICE')->get();
        $data = [];
        $data1 = [];
        foreach ($list_feedback_room as $item) {
            $customer = Customer::find($item->customer_id);
            $room_type = RoomType::find($item->room_type_id);
            $employee = Employee::find($item->employee_id);
            $position =  Position::find($employee->position_id);

            $data[] = [
                'id' => $item->id,
                'customer_name' => $customer->full_name,
                'room_type_name' => $room_type->room_type_name,
                'rating' => $item->rating,
                'title' => $item->title,
                'comment' => $item->comment,
                'date_request' => $item->date_request,
                'date_response' => $item->date_response,
                'employee_name' => $employee->full_name,
                'position' => $position->position_name,
                'image' => $item->image,
            ];
        }
        foreach ($list_feedback_service as $item1) {
            $customer = Customer::find($item1->customer_id);
            $service = Service::find($item1->service_id);
            $employee = Employee::find($item1->employee_id);
            $position =  Position::find($employee->position_id);
            $data1 [] = [
                'id' => $item1->id,
                'customer_name' => $customer->full_name,
                'service_name' => $service->service_name,
                'rating' => $item1->rating,
                'title' => $item1->title,
                'comment' => $item1->comment,
                'date_request' => $item1->date_request,
                'date_response' => $item1->date_response,
                'employee_name' => $employee->full_name,
                'position' => $position->position_name,
                'image' => $item1->image,
            ];
        }
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_feedback_room' => $data,
            'list_feedback_service' => $data1,
        ], 200);
    }
    public function indexFeedbackEmployee()
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $employee = DB::table('employees')->where('account_id', '=', $user->id)->first();

            if ($employee) {
                $list_feedback_room = DB::table('feedback')
                    ->where('feedback_status', '=', 'Feedbacked')
                    ->where('feedback_type', '=', 'ROOM')
                    ->where('employee_id', '=', $employee->id)->get();
                $list_feedback_service = DB::table('feedback')
                    ->where('feedback_status', '=', 'Feedbacked')
                    ->where('feedback_type', '=', 'SERVICE')
                    ->where('employee_id', '=', $employee->id)->get();
                $data = [];
                $data1 = [];
                foreach ($list_feedback_room as $item) {
                    $customer = Customer::find($item->customer_id);
                    $room_type = RoomType::find($item->room_type_id);
                    $data[] = [
                        'id' => $item->id,
                        'customer_name' => $customer->full_name,
                        'room_type_name' => $room_type->room_type_name,
                        'rating' => $item->rating,
                        'title' => $item->title,
                        'comment' => $item->comment,
                        'date_request' => $item->date_request,
                        'date_response' => $item->date_response,
                        'image' => $item->image,
                    ];
                }
                foreach ($list_feedback_service as $item1) {
                    $customer = Customer::find($item1->customer_id);
                    $service = Service::find($item1->service_id);
                    $data1 [] = [
                        'id' => $item1->id,
                        'customer_name' => $customer->full_name,
                        'service_name' => $service->service_name,
                        'rating' => $item1->rating,
                        'title' => $item1->title,
                        'comment' => $item1->comment,
                        'date_request' => $item1->date_request,
                        'date_response' => $item1->date_response,
                        'image' => $item1->image,
                    ];
                }
                return response()->json([
                    'message' => 'Query successfully!',
                    'status' => 200,
                    'list_feedback_room' => $data,
                    'list_feedback_service' => $data1,
                ], 200);
            }
        }
    }
    public function getFeedbackByEmployee(Request $request, string $id)
    {
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $employee = DB::table('employees')->where('account_id', '=', $user->id)->first();
            if ($employee) {
                $employee_id = DB::table('feedback')->where('id', '=', $id)->value('employee_id');
                if (!$employee_id) {
                    $feedback = DB::table('feedback')->where('id', '=', $id)->update([
                        'date_response' => Carbon::now(),
                        'feedback_status' =>  'Feedbacked',
                        'employee_id' =>  $employee->id,
                    ]);
                    if ($feedback) {
                        return response()->json([
                            'message' => 'Updated Successfully',
                            'status' => 200,
                            'feedback' => $feedback,

                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Updated Failed!',
                            'status' => 400,
                            'employee' => $employee,

                        ]);
                    }
                }
            }
        }
    }
    public function deleteFeedback($id)
    {
        // Xóa các dữ liệu liên quan (ReservationRooms, ...) trước khi xóa bill_room
        $feedback = DB::table('feedback')->where('id', '=', $id)->delete();
        return response()->json([
            'status' => 200,
            'message' => 'successfully',

        ]);
    }
    public function storeFeedbackServiceByCustomer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'title' => 'required',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();

            if ($customer) {
                $feedback = Feedback::create([

                    'customer_id' => $customer->id,
                    'service_id' => $id,
                    'feedback_status' => 'Not feedbacked yet',
                    'feedback_type' => 'SERVICE',
                    'rating' => $request->rating,
                    'title' => $request->title,
                    'comment' => $request->comment,
                    'date_request' => Carbon::now(),
                    'image' => $request->image,


                ]);

                if ($feedback) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Feedback created Successfully',
                        'feedback' => $feedback,
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Data not found!',
                        'status' => 404,
                    ], 404);
                }
            }
        }
    }
    public function storeFeedbackRoomByCustomer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'title' => 'required',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status_code' => 400,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }
        $user = auth()->user();
        // Kiểm tra token hợp lệ và người dùng đã đăng nhập
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } else {
            $customer = DB::table('customers')->where('account_id', '=', $user->id)->first();

            if ($customer) {
                $feedback = Feedback::create([
                    'customer_id' => $customer->id,
                    'room_type_id' => $id,
                    'feedback_status' => 'Not feedbacked yet',
                    'feedback_type' => 'ROOM',
                    'rating' => $request->rating,
                    'title' => $request->title,
                    'comment' => $request->comment,
                    'date_request' => Carbon::now(),
                    'image' => fake()->imageUrl(),
                ]);

                if ($feedback) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Feedback created Successfully',
                        'feedback' => $feedback,
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Data not found!',
                        'status' => 404,
                    ], 404);
                }
            }
        }
    }
    public function getAllFeedbackRooms()
    {
        $list_feedback_rooms = DB::table('feedback')->where('feedback_type', '=', 'ROOM')->get();
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_feedback_rooms' => $list_feedback_rooms,
        ], 200);
    }

    public function getAllFeedbackServices()
    {
        $list_feedback_services = DB::table('feedback')->where('feedback_type', '=', 'SERVICE')->get();
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_feedback_services' => $list_feedback_services,
        ], 200);
    }

    public function getTotalFeedbacksByRoomTypeId($id)
    {
        $total_feedback_rooms = DB::table('feedback')->where('room_type_id', '=', $id)->get();
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'total_feedback_rooms' => count($total_feedback_rooms),
        ], 200);
    }

    public function getTotalFeedbacksByServiceId($id)
    {
        $total_feedback_services = DB::table('feedback')->where('service_id', '=', $id)->get();
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'total_feedback_services' => count($total_feedback_services),
        ], 200);
    }

    public function getAverageRatingByRoomTypeId($id)
    {
        $total_rating_room_type = DB::table('feedback')->where('room_type_id', '=', $id)->sum('rating');
        $rating_count = DB::table('feedback')->where('room_type_id', '=', $id)->count();
        $average_rating_room_type = $rating_count > 0 ? $total_rating_room_type / $rating_count : 0;

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'average_rating_room_type' => $average_rating_room_type,
        ], 200);
    }

    public function getAverageRatingByServiceId($id)
    {
        $total_rating_service = DB::table('feedback')->where('service_id', '=', $id)->sum('rating');
        $rating_count = DB::table('feedback')->where('service_id', '=', $id)->count();
        $average_rating_service = $rating_count > 0 ? $total_rating_service / $rating_count : 0;

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'average_rating_service' => $average_rating_service,
        ], 200);
    }

    public function getTotalVerifiedFeedbackByRoomTypeId($id)
    {
        $total_verified_feedback_room_types = DB::table('feedback')
            ->where('room_type_id', '=', $id)
            ->where('feedback_status', '=', 'Feedbacked')
            ->count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'total_verified_feedback_room_types' => $total_verified_feedback_room_types,
        ], 200);
    }

    public function getTotalVerifiedFeedbackByServiceId($id)
    {
        $total_verified_feedback_services = DB::table('feedback')
            ->where('service_id', '=', $id)
            ->where('feedback_status', '=', 'Feedbacked')
            ->count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'total_verified_feedback_services' => $total_verified_feedback_services,
        ], 200);
    }

    public function getFeedbackByRoomTypeId($id)
    {
        $list_feedback_rooms = DB::table('feedback')
            ->where('room_type_id', '=', $id)
            ->join('customers', 'customers.id', '=', 'feedback.customer_id')
            ->join('accounts', 'accounts.id', '=', 'customers.account_id')
            ->select([
                'feedback.id', 'date_request', 'date_response', 'title', 'rating', 'comment', 'feedback_status',
                'feedback.customer_id', 'customers.account_id', 'customers.full_name', 'accounts.email',
                'customers.gender', 'customers.birthday', 'customers.CMND', 'customers.address', 'customers.phone',
                'customers.ranking_point', 'accounts.username', 'accounts.avatar'
            ])
            ->get();
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_feedback_rooms' => $list_feedback_rooms,
        ], 200);
    }

    public function getFeedbackByServiceId($id)
    {
        $list_feedback_services = DB::table('feedback')
            ->where('service_id', '=', $id)
            ->join('customers', 'customers.id', '=', 'feedback.customer_id')
            ->join('accounts', 'accounts.id', '=', 'customers.account_id')
            ->select([
                'feedback.id', 'date_request', 'date_response', 'title', 'rating', 'comment', 'feedback_status',
                'feedback.customer_id', 'customers.account_id', 'customers.full_name', 'accounts.email',
                'customers.gender', 'customers.birthday', 'customers.CMND', 'customers.address', 'customers.phone',
                'customers.ranking_point', 'accounts.username', 'accounts.avatar'
            ])
            ->get();
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_feedback_services' => $list_feedback_services,
        ], 200);
    }

    public function paging($id, $type, $page_number, $num_of_page)
    {
        if ($type == 'room') {
            $list_feedbacks = DB::table('feedback')
                ->where('room_type_id', '=', $id)
                ->join('customers', 'customers.id', '=', 'feedback.customer_id')
                ->join('accounts', 'accounts.id', '=', 'customers.account_id')
                ->join('rankings', 'rankings.id', '=', 'customers.ranking_id')
                ->select([
                    'feedback.id', 'date_request', 'date_response', 'title', 'rating', 'comment', 'feedback_status',
                    'feedback.customer_id', 'customers.account_id', 'customers.full_name', 'accounts.email',
                    'customers.gender', 'customers.birthday', 'customers.CMND', 'customers.address', 'customers.phone',
                    'customers.ranking_point', 'rankings.ranking_name', 'accounts.username', 'accounts.avatar'
                ])
                ->get();
        } else if ($type == 'service') {
            $list_feedbacks = DB::table('feedback')
                ->where('service_id', '=', $id)
                ->join('customers', 'customers.id', '=', 'feedback.customer_id')
                ->join('accounts', 'accounts.id', '=', 'customers.account_id')
                ->join('rankings', 'rankings.id', '=', 'customers.ranking_id')
                ->select([
                    'feedback.id', 'date_request', 'date_response', 'title', 'rating', 'comment', 'feedback_status',
                    'feedback.customer_id', 'customers.account_id', 'customers.full_name', 'accounts.email',
                    'customers.gender', 'customers.birthday', 'customers.CMND', 'customers.address', 'customers.phone',
                    'customers.ranking_point', 'rankings.ranking_name', 'accounts.username', 'accounts.avatar'
                ])
                ->get();
        }

        $data = [];

        if (count($list_feedbacks) > 0) {
            if (count($list_feedbacks) % $num_of_page == 0) {
                $page = count($list_feedbacks) / $num_of_page;
            } else {
                $page = (int)(count($list_feedbacks) / $num_of_page) + 1;
            }

            $start = ($page_number - 1) * $num_of_page;
            $data = [];

            if ($page_number == $page) {
                for ($i = $start; $i < count($list_feedbacks); $i++) {
                    $data[] = [
                        "id" => $list_feedbacks[$i]->id,
                        "date_request" => $list_feedbacks[$i]->date_request,
                        "date_response" => $list_feedbacks[$i]->date_response,
                        "title" => $list_feedbacks[$i]->title,
                        "rating" => $list_feedbacks[$i]->rating,
                        "comment" => $list_feedbacks[$i]->comment,
                        "feedback_status" => $list_feedbacks[$i]->feedback_status,
                        "customer_id" => $list_feedbacks[$i]->customer_id,
                        "account_id" => $list_feedbacks[$i]->account_id,
                        "full_name" => $list_feedbacks[$i]->full_name,
                        "email" => $list_feedbacks[$i]->email,
                        "gender" => $list_feedbacks[$i]->gender,
                        "birthday" => $list_feedbacks[$i]->birthday,
                        "CMND" => $list_feedbacks[$i]->CMND,
                        "address" => $list_feedbacks[$i]->address,
                        "phone" => $list_feedbacks[$i]->phone,
                        "ranking_point" => $list_feedbacks[$i]->ranking_point,
                        "ranking_name" => $list_feedbacks[$i]->ranking_name,
                        "username" => $list_feedbacks[$i]->username,
                        "avatar" => $list_feedbacks[$i]->avatar,
                    ];
                }
            } else if ($page_number > $page) {
                $data = [];
            } else {
                for ($i = $start; $i < $start + $num_of_page; $i++) {
                    $data[] = [
                        "id" => $list_feedbacks[$i]->id,
                        "date_request" => $list_feedbacks[$i]->date_request,
                        "date_response" => $list_feedbacks[$i]->date_response,
                        "title" => $list_feedbacks[$i]->title,
                        "rating" => $list_feedbacks[$i]->rating,
                        "comment" => $list_feedbacks[$i]->comment,
                        "feedback_status" => $list_feedbacks[$i]->feedback_status,
                        "customer_id" => $list_feedbacks[$i]->customer_id,
                        "account_id" => $list_feedbacks[$i]->account_id,
                        "full_name" => $list_feedbacks[$i]->full_name,
                        "email" => $list_feedbacks[$i]->email,
                        "gender" => $list_feedbacks[$i]->gender,
                        "birthday" => $list_feedbacks[$i]->birthday,
                        "CMND" => $list_feedbacks[$i]->CMND,
                        "address" => $list_feedbacks[$i]->address,
                        "phone" => $list_feedbacks[$i]->phone,
                        "ranking_point" => $list_feedbacks[$i]->ranking_point,
                        "ranking_name" => $list_feedbacks[$i]->ranking_name,
                        "username" => $list_feedbacks[$i]->username,
                        "avatar" => $list_feedbacks[$i]->avatar,
                    ];
                }
            }
        }
        return response()->json([
            'status' => 200,
            'list_feedbacks' => $data,
        ]);
    }
}
