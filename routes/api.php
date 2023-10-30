<?php

use App\Http\Controllers\Api\AuthinticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\GoogleCalendar\Event;


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

Route::controller(AuthinticationController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('make-appointment' , 'makeAppointment');
    Route::post('delete_appointment/{id}' , 'deleteAppointment');

});

Route::middleware('auth:sanctum')->group(function (){
    Route::controller(AuthinticationController::class)->group(function(){
        Route::get('logout', 'logout');
    });
});
