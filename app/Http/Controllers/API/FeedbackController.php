<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function index()
    {
        $list_feedbacks = DB::table('feedback')->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'list_feedbacks' => $list_feedbacks,
        ], 200);
    }

    public function show($id)
    {
        $feedback = DB::table('feedback')->find($id);
        if ($feedback) {
            return response()->json([
                'message' => 'Query successfully!',
                'status' => 200,
                'feedback' => $feedback,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
                'status' => 404,
                'feedback' => $feedback,
            ], 404);
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

    public function getTotalFeedbacksByRoomTypeId($id) {
        $total_feedback_rooms = DB::table('feedback')->where('room_type_id', '=', $id)->get();
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'total_feedback_rooms' => count($total_feedback_rooms),
        ], 200);
    }

    public function getTotalFeedbacksByServiceId($id) {
        $total_feedback_services = DB::table('feedback')->where('service_id', '=', $id)->get();
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'total_feedback_services' => count($total_feedback_services),
        ], 200);
    }

    public function getAverageRatingByRoomTypeId($id) {
        $total_rating_room_type = DB::table('feedback')->where('room_type_id', '=', $id)->sum('rating');
        $rating_count = DB::table('feedback')->where('room_type_id', '=', $id)->count();
        $average_rating_room_type = $rating_count > 0 ? $total_rating_room_type / $rating_count : 0;

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'average_rating_room_type' => $average_rating_room_type,
        ], 200);
    }

    public function getAverageRatingByServiceId($id) {
        $total_rating_service = DB::table('feedback')->where('service_id', '=', $id)->sum('rating');
        $rating_count = DB::table('feedback')->where('service_id', '=', $id)->count();
        $average_rating_service = $rating_count > 0 ? $total_rating_service / $rating_count : 0;

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'average_rating_service' => $average_rating_service,
        ], 200);
    }

    public function getTotalVerifiedFeedbackByRoomTypeId($id) {
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

    public function getTotalVerifiedFeedbackByServiceId($id) {
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
