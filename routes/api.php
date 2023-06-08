<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\BillRoomController;
use App\Http\Controllers\API\BillServiceController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\FloorController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\RoomTypeController;
use App\Http\Controllers\API\ServiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public route
Route::group([
    'middleware' => ['force.json.response', 'api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::patch('/reset-password/request', [AccountController::class, 'requestResetCode']);
    Route::patch('/reset-verify-code/{email}', [AccountController::class, 'resetVerifyCode']);
    Route::patch('/reset-password/{email}/{code}', [AccountController::class, 'resetPassword']);
});

// Auth API
Route::group([
    'middleware' => ['force.json.response', 'api', 'api.auth'],
    'prefix' => 'auth',
], function ($router) {
    // Authenticate
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
    Route::patch('/changePassword', [AccountController::class, 'changePassword']);

    //BillRoomDetail
    Route::get('/show-bill-room-detail/{id}', [BillRoomController::class, 'findBillRoomDetail']);

    // Areas
    Route::get('/areas', [AreaController::class, 'index']);
    Route::get('/areas/total', [AreaController::class, 'getTotalAreas']);
    Route::get('/areas/{id}', [AreaController::class, 'show']);

    // Floors
    Route::get('/floors', [FloorController::class, 'index']);
    Route::get('/floors/total', [FloorController::class, 'getTotalFloors']);
    Route::get('/floors/{id}', [FloorController::class, 'show']);

    // Rooms
    Route::get('/room', [RoomController::class, 'index']);
    Route::get('/room/room-type/{id}', [RoomController::class, 'getRoomsByRoomTypeId']);
    Route::get('/room/{id}', [RoomController::class, 'show']);
    Route::get('/reserved-room', [RoomController::class, 'getReservedRooms']);

    // Room Types
    Route::get('/room-types', [RoomTypeController::class, 'index']);
    Route::get('/room-types/total/', [RoomTypeController::class, 'getTotalRoomTypes']);
    Route::get('/room-types/total-rooms/{id}', [RoomTypeController::class, 'getTotalNumerOfRoomByRoomTypeId']);
    Route::get('/room-types/list-rooms/{id}', [RoomTypeController::class, 'getListRoomsByRoomTypeId']);
    Route::get('/room-types/lowest-price', [RoomTypeController::class, 'getLowestPrice']);
    Route::get('/room-types/highest-price', [RoomTypeController::class, 'getHighestPrice']);
    Route::get('/room-types/smallest-size', [RoomTypeController::class, 'getSmallestRoomSize']);
    Route::get('/room-types/biggest-size', [RoomTypeController::class, 'getBiggestRoomSize']);
    Route::get('/room-types/names', [RoomTypeController::class, 'getListRoomTypeName']);
    Route::get('/room-types/list-lowest-price', [RoomTypeController::class, 'getTop5LowestPrice']);
    Route::get('/room-types/bedroom-names', [RoomTypeController::class, 'getBedroomTypeNames']);
    Route::get('/room-types/room-names', [RoomTypeController::class, 'getRoomTypeNames']);
    Route::get('/room-types/random/{id}', [RoomTypeController::class, 'getRandomRoomTypes']);
    Route::post('/room-types/filter', [RoomTypeController::class, 'filterRoomType']);
    Route::post('/room-types/paginate/{page_number}/{num_of_page}', [RoomTypeController::class, 'paging']);
    Route::get('/room-types/{id}', [RoomTypeController::class, 'show']);


    // Services
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/total/', [ServiceController::class, 'getTotalServices']);
    Route::get('/services/list-lowest-price', [ServiceController::class, 'getTop5LowestPrice']);
    Route::get('/services/lowest-price', [ServiceController::class, 'getLowestPrice']);
    Route::get('/services/highest-price', [ServiceController::class, 'getHighestPrice']);
    Route::get('/services/names', [ServiceController::class, 'getListServiceNames']);
    Route::get('/services/random/{id}', [ServiceController::class, 'getRandomServices']);
    Route::post('/services/filter', [ServiceController::class, 'filterService']);
    Route::post('/services/paginate/{page_number}/{num_of_page}', [ServiceController::class, 'paging']);
    Route::get('/services/{id}', [ServiceController::class, 'show']);

    // Feedbacks
    Route::get('/feedbacks', [FeedbackController::class, 'index']);
    Route::get('/feedbacks/{id}/{type}/paginate/{page_number}/{num_of_page}', [FeedbackController::class, 'paging']); // id = room_type_id or service_id
    Route::get('/feedbacks/room', [FeedbackController::class, 'getAllFeedbackRooms']);
    Route::get('/feedbacks/service', [FeedbackController::class, 'getAllFeedbackServices']);
    Route::get('/feedbacks/average-rate/room/{room_type_id}', [FeedbackController::class, 'getAverageRatingByRoomTypeId']);
    Route::get('/feedbacks/average-rate/service/{service_id}', [FeedbackController::class, 'getAverageRatingByServiceId']);
    Route::get('/feedbacks/room-type/{room_type_id}', [FeedbackController::class, 'getFeedbackByRoomTypeId']);
    Route::get('/feedbacks/service/{service_id}', [FeedbackController::class, 'getFeedbackByServiceId']);
    Route::get('/feedbacks/room-type/total/{room_type_id}', [FeedbackController::class, 'getTotalFeedbacksByRoomTypeId']);
    Route::get('/feedbacks/service/total/{service_id}', [FeedbackController::class, 'getTotalFeedbacksByServiceId']);
    Route::get('/feedbacks/room-type/total-verified/{room_type_id}', [FeedbackController::class, 'getTotalVerifiedFeedbackByRoomTypeId']);
    Route::get('/feedbacks/service/total-verified/{service_id}', [FeedbackController::class, 'getTotalVerifiedFeedbackByServiceId']);
    Route::get('/feedbacks/{id}', [FeedbackController::class, 'show']);
});

// Customer API
Route::group([
    'middleware' => ['force.json.response', 'api', 'api.auth', 'auth.customer'],
    'prefix' => 'customer',
], function ($router) {
    //personal information
    Route::get('/account-customer', [CustomerController::class, 'getCustomerByAccountId']);
    Route::get('/history-bill-customer', [CustomerController::class, 'findHistoryBillCustomerByID']);
    Route::get('/book-bill-customer', [CustomerController::class, 'findBookBillCustomerByID']);

    // Payment
    Route::post('/vnpay_payment', [PaymentController::class, 'vnpay_payment']);
    Route::post('/save_payment_data', [BillRoomController::class, 'savePaymentData']);

   
    // tạo bill service
    Route::post('/store-bill-service', [BillServiceController::class, 'storeBillService']);
    Route::get('/ranking/{account_id}', [CustomerController::class, 'getRankingNameByAccountId']);
    Route::get('/search/{search}', [CustomerController::class, 'searchByParams']);
    Route::patch('/update-customer', [CustomerController::class, 'updateCutomerByAccountId']);
});

// Employee API
Route::group([
    'middleware' => ['force.json.response', 'api', 'api.auth', 'auth.employee'],
    'prefix' => 'employee',
], function ($router) {
    // personal information
    Route::get('/account-employee', [EmployeeController::class, 'getEmployeeByAccountId']);
    Route::patch('/update-employee', [EmployeeController::class, 'updateEmployeeByAccountId']);
    // customer  
    Route::get('/list-customer', [CustomerController::class, 'index']);
    Route::get('/show-customer/{id}', [CustomerController::class, 'ShowCustomerByID']);
    Route::get('/find-customer/find', [CustomerController::class, 'findCustomer']);
    Route::get('/show-bill-customer/{id}', [CustomerController::class, 'findBillByID']);
    Route::get('/get-total-amount/{id}', [CustomerController::class, 'getTotalAmount']);
    //bill room
    Route::get('/list-bill-room', [BillRoomController::class, 'findBillRoom']);
    Route::get('/list-history-room', [BillRoomController::class, 'findHistoryRoom']);
    Route::get('/list-cancel-room', [BillRoomController::class, 'findCancelRoom']);
    //bill service
    Route::get('/list-bill-service', [BillServiceController::class, 'findBillService']);
    Route::get('/list-history-service', [BillServiceController::class, 'findHistoryService']);
    Route::get('/list-cancel-service', [BillServiceController::class, 'findCancelService']);


    Route::get('/list', [EmployeeController::class, 'index']);
    Route::get('/{id}', [EmployeeController::class, 'show']);
    Route::get('/search/{search}', [EmployeeController::class, 'searchByParams']);
    Route::get('/find/{id}', [EmployeeController::class, 'employeeFindID']);

    Route::patch('/{id}', [EmployeeController::class, 'update']);
});

// Admin API

Route::group([
    'middleware' => ['force.json.response', 'api', 'api.auth', 'auth.admin'],
    'prefix' => 'admin',
], function ($router) {
    // personal information

  Route::get('/account-admin', [AdminController::class, 'getAdminByAccountId']);
  Route::patch('/update-admin', [AdminController::class, 'updateAdminByAccountId']); 
  // Cutomer
  Route::get('/list-customer', [CustomerController::class, 'index']);
  Route::get('/show-customer/{id}', [CustomerController::class, 'ShowCustomerByID']);
  Route::get('/find-customer/find', [CustomerController::class, 'findCustomer']);
  Route::get('/show-bill-customer/{id}', [CustomerController::class, 'findBillByID']);
  Route::get('/get-total-amount/{id}', [CustomerController::class, 'getTotalAmount']);

  // Employee
  Route::get('/list-employee', [EmployeeController::class, 'index']);
  Route::get('/find-employee/{id}',[EmployeeController::class, 'employeeFindID']);
  Route::post('/store-employee', [EmployeeController::class, 'store']);
  Route::patch('/quit-employee/{id}', [EmployeeController::class, 'quitEmployeeByID']);
 
  // Admin
  Route::get('/{id}',[AdminController::class, 'show']);
  Route::get('/search/{search}', [AdminController::class, 'searchByParams']);
  Route::get('/find/{id}',[AdminController::class, 'adminFindID']);
  Route::patch('/{id}',[AdminController::class, 'update']);
});