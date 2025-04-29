<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\LeaveDetailsController;
use App\Http\Controllers\OBController;
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
    Route::get('/get_ob_data', [OBController::class, 'getOB']);
    
    Route::get('/leave_type', function(){
        return LeaveType::all();
    });

    // Route::get('/test-time-validation', function () {
    //     $testTimes = ['5:00 PM', '7:30 PM', '12:00 AM', '12:00 PM', '1:00 AM', '1:00 PM', '0:00', '13:00'];

    //     $results = [];
    //     foreach ($testTimes as $time) {
    //         if (preg_match('/^(0?[1-9]|1[0-2]):([0-5]\d)( ?[AP]M)?$/i', $time, $matches)) {
    //             $results[] = "$time is valid.";
    //         } else {
    //             $results[] = "$time is invalid.";
    //         }
    //     }

    //     return response()->json($results);
    // });

    
    Route::get('/get_ot_request', [OTRequestController::class, 'getOTReq']);
    Route::get('/get_all_ot_request', [OTRequestController::class, 'getAllOtReq']);
    Route::post('/submit_ot_request', [OTRequestController::class, 'submitOtReq']);
    Route::post('/handle_ot_request', [OTRequestController::class, 'handleOtReq']);
    Route::get('/ot_request_details', [OTRequestController::class, 'getApprovedOtReq']);
    Route::post('/add_leave', [LeaveDetailsController::class, 'addLeave']);
    Route::post('/update_leave', [LeaveDetailsController::class, 'updateLeave']);
    Route::post('/handle_leave_request', [LeaveDetailsController::class, 'handleLeaveReq']);
    Route::get('/get_all_ob', [OBController::class, 'getAllObReq']);
    Route::post('/add_ob', [OBController::class, 'addOBForm']);
    Route::post('/update_ob', [OBController::class, 'updateOb']);
    Route::post('/handle_ob_request', [OBController::class, 'handleObReq']);
    Route::get('/ob_request_details', [OBController::class, 'getApprovedOb']);
    Route::get('/{any}', [SpaController::class, 'index'])->where('any','.*');
});