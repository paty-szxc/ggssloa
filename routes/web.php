<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\LeaveDetailsController;
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

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy']);
    Route::get('/user', [UsersInfoController::class, 'getUserInfo']);
    Route::get('/employee', [EmployeesController::class, 'getEmployee']);
    Route::get('/leave_data', [LeaveDetailsController::class, 'getLeaveDetails']);
    Route::get('/leave_details', [LeaveDetailsController::class, 'getLeaveDetailsHome']);

    Route::get('/leave_type', function(){
        return LeaveType::all();
    });

    Route::post('/add_leave', [LeaveDetailsController::class, 'addLeave']);
    Route::post('/update_leave', [LeaveDetailsController::class, 'updateLeave']);
    Route::get('/{any}', [SpaController::class, 'index'])->where('any','.*');
});