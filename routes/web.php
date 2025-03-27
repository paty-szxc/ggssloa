<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\LeaveDetailsController;
use App\Http\Controllers\OTRequestController;
use App\Http\Controllers\SpaController;
use App\Http\Controllers\UsersInfoController;
use Illuminate\Support\Facades\Route;
use App\Models\LeaveType;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/register', [RegisterController::class, 'create']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy']);
    Route::get('/user', [UsersInfoController::class, 'getUserInfo']);
    Route::get('/employee', [EmployeesController::class, 'getEmployee']);
    Route::get('/leave_data', [LeaveDetailsController::class, 'getLeaveDetails']);
    Route::get('/leave_req_details', [LeaveDetailsController::class, 'getLeaveReq']);
    
    Route::get('/leave_type', function(){
        return LeaveType::all();
    });
    
    Route::get('/get_ot_request', [OTRequestController::class, 'getOTReq']);
    Route::get('/get_all_ot_request', [OTRequestController::class, 'getAllOtReq']);
    Route::post('/submit_ot_request', [OTRequestController::class, 'submitOtReq']);
    Route::post('/handle_ot_request', [OTRequestController::class, 'handleOtReq']);
    Route::get('/ot_request_details', [OTRequestController::class, 'getApprovedOtReq']);
    Route::post('/add_leave', [LeaveDetailsController::class, 'addLeave']);
    Route::post('/update_leave_home', [LeaveDetailsController::class, 'update_leave_home']);
    Route::post('/handle_leave_request', [LeaveDetailsController::class, 'handleLeaveReq']);
    Route::get('/{any}', [SpaController::class, 'index'])->where('any','.*');
});