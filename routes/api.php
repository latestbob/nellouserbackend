<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('/auth')->group(function (){
    Route::post('/login', 'Api/AuthController@loginCustomer');
    Route::post('/register', 'Api/AuthController@registerCustomer');
});

Route::prefix('/profile')->group(function() {
    Route::put('/update', 'Api/ProfileController@updateCustomer');
    Route::put('/picture', 'Api/ProfileController@uploadPicture');
    Route::get('health-history', 'Api/ProfileController@fetchHealthHistory');
    Route::get('medical-reports', 'Api/ProfileController@fetchMedicalReports');
});

Route::get('/doctors', 'Api/DoctorController@fetchDoctors');
Route::get('/medical-centers', 'Api/MedicalCenterController@fetchCenters');

Route::post('/appointments/book', 'Api/AppointmentController@bookAppointment');

