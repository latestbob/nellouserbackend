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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::prefix('/auth')->group(function (){
    Route::get('/', 'Api\AuthController@getToken')->name('api.auth');
    Route::post('/login', 'Api\AuthController@loginCustomer');
    Route::post('/register', 'Api\AuthController@registerCustomer');
});

Route::prefix('/profile')->middleware('jwt.auth')->group(function() {
    Route::post('/update', 'Api\ProfileController@updateCustomer');
    Route::post('/picture', 'Api\ProfileController@uploadPicture');
    Route::get('/health-history', 'Api\ProfileController@fetchHealthHistory');
    //Route::get('/medical-reports', 'Api\ProfileController@fetchMedicalReports');
    //Route::get('/reorder-drugs', 'Api\ProfileController@reorderDrugs');
});

Route::post('/password/change', 'Api\ProfileController@changePassword')->middleware('jwt.auth');
Route::post('/contact/message', 'ContactController@sendMessage')->middleware('jwt.auth');

Route::get('/doctors', 'Api\DoctorController@fetchDoctors')->middleware('jwt.auth');
Route::get('/health-tips', 'HealthTipController@index')->middleware('jwt.auth');
Route::get('/health-centers', 'HealthCenterController@index')->middleware('jwt.auth');

Route::prefix('/appointments')->middleware('jwt.auth')->group(function() {
    Route::post('/book', 'AppointmentController@bookAppointment');
    Route::get('/view','AppointmentController@viewAppointment');
    Route::put('/update', 'AppointmentController@updateAppointment');
    Route::put('/cancel', 'AppointmentController@cancelAppointment');
});


Route::prefix('/nello')->middleware('nello.auth')->group(function() {
    Route::put('/profile/update', 'Api\ProfileController@updateCustomer');
    Route::post('/profile/picture', 'Api\ProfileController@uploadPicture');
});

Route::prefix('/import')->middleware('nello.auth')->group(function(){
    Route::match(['post', 'put', 'delete'], '/users', 'ImportController@importUser');
    Route::post('/health-centers', 'ImportController@importHealthCenter');
    Route::post('/encounter', 'ImportController@importEncounter');
    Route::post('/health-tip', 'ImportController@importHealthTip');
    Route::post('/investigation', 'ImportController@importInvestigation');
    Route::post('/invoice', 'ImportController@importInvoice');
    Route::post('/medication', 'ImportController@importMedication');
    Route::post('/payment', 'ImportController@importPayment');
    Route::post('/procedure', 'ImportController@importProcedure');
    Route::post('/vital', 'ImportController@importVital');
});
