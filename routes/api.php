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

//Route::middleware('jwt.auth')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::prefix('/auth')->group(function (){
    Route::get('/', 'Api\AuthController@getToken')->name('api.auth');
    Route::post('/login', 'Api\AuthController@loginCustomer')->name('login');
    Route::post('/forgot-password', 'Api\AuthController@forgotPasswordCustomer');
    Route::post('/reset-password', 'Api\AuthController@resetPasswordCustomer');
    Route::post('/register', 'Api\AuthController@registerCustomer');
    Route::get('/user', 'Api\AuthController@getUser')->middleware('jwt.auth');

    Route::post('/verify', 'Api\AuthController@verifyToken');
});

Route::prefix('/admin')->group(function (){

    Route::prefix('/auth')->group(function (){
        Route::post('/login', 'Api\Admin\AuthController@loginAdmin')->name('login');
        Route::post('/forgot-password', 'Api\Admin\AuthController@forgotPasswordAdmin');
        Route::post('/reset-password', 'Api\Admin\AuthController@resetPasswordAdmin');
        Route::get('/user', 'Api\Admin\AuthController@getUser');
    });

    Route::prefix('/feedback')->group(function() {
        Route::post('/view','Api\Admin\FeedBackController@getFeedbacks');
        Route::post('/analysis','Api\Admin\FeedBackController@getFeedbackAnalysis');
    });

    Route::prefix('/order')->group(function (){
        Route::post('/drug/view', 'Api\Admin\OrderController@drugOrders');
        Route::post('/drug/{cart_uuid}/items', 'Api\Admin\OrderController@drugOrderItems');
        Route::post('/drug/item/action', 'Api\Admin\OrderController@drugOrderItemAction');
        Route::post('/drug/item/add-prescription', 'Api\Admin\OrderController@addPrescription');
    });

});

Route::prefix('/profile')->middleware('jwt.auth')->group(function() {
    Route::post('/update', 'Api\ProfileController@updateCustomer');
    Route::post('/picture', 'Api\ProfileController@uploadPicture');

    Route::get('/encounters', 'Api\ProfileController@encounters');
    Route::get('/medications', 'Api\Profilecontroller@medications');
    Route::get('/vital-signs', 'Api\ProfileController@vitalSigns');
    Route::get('/procedures', 'Api\ProfileController@procedures');
    Route::get('/investigations', 'Api\ProfileController@investigations');
    Route::get('/invoices', 'Api\ProfileController@invoices');
    Route::get('/payments', 'Api\ProfileController@payments');

    Route::get('/health-history', 'Api\ProfileController@fetchHealthHistory');
    Route::get('/summary', 'Api\ProfileController@inBrief');
    //Route::get('/medical-reports', 'Api\ProfileController@fetchMedicalReports');
    //Route::get('/reorder-drugs', 'Api\ProfileController@reorderDrugs');

    Route::get('/appointments', 'Api\ProfileController@appointments');
});

Route::post('/password/change', 'Api\ProfileController@changePassword')->middleware('jwt.auth');
Route::post('/contact/message', 'ContactController@sendMessage')->middleware('jwt.auth');

Route::get('/drugs', 'Api\DrugController@index');
Route::get('/doctors', 'Api\DoctorController@fetchDoctors'); //->middleware('jwt.auth');
Route::post('/doctor/rate', 'Api\DoctorController@rateDoctor')->middleware('jwt.auth');
Route::get('/doctors/specializations', 'Api\DoctorController@fetchSpecializations');
Route::get('/health-tips', 'HealthTipController@index')->middleware('jwt.auth');
Route::get('/health-tip', 'HealthTipController@lastTip')->middleware('jwt.auth');
Route::get('/health-centers', 'HealthCenterController@index')->middleware('jwt.auth');
Route::get('/vendors', 'Api\VendorController@getAllVendors');

Route::prefix('/feedback')->group(function() {
    Route::post('/create', 'Api\FeedBackController@create');
});

Route::post('/file-upload', 'FileController@fileUpload');
Route::post('/order/drug', 'Api\OrderController@checkout');
Route::post('/order/drug/confirm-payment', 'Api\OrderController@confirmPayment');

Route::prefix('/cart')->group(function() {
    Route::post('/items', 'Api\CartController@getItems')->name('get_cart_items');
    Route::post('/add', 'Api\CartController@addToCart')->name('add_to_cart');
    Route::post('/remove', 'Api\CartController@removeFromCart')->name('remove_from_cart');
    Route::post('/update', 'Api\CartController@updateCart')->name('update_cart');
    Route::post('/add-prescription', 'Api\CartController@addPrescription')->name('add_prescription_to_cart_item');
});

Route::prefix('/appointments')->middleware('jwt.auth')->group(function() {
    Route::get('/', 'AppointmentController@index');
    Route::post('/book', 'AppointmentController@bookAppointment');
    Route::get('/view','AppointmentController@viewAppointment');
    Route::post('/update', 'AppointmentController@update');
    Route::post('/cancel', 'AppointmentController@cancel');
    Route::get('/pending', 'AppointmentController@pending');
});

Route::get('/users/{user:uuid}/appointments', 'AppointmentController@fetchUserAppointments');
//Route::get('/users/{user:uuid}/appointments', 'AppointmentController@fetchUserAppointments');

Route::prefix('/nello')->middleware('nello.auth')->group(function() {
    Route::post('/users/create', 'Api\AuthController@nelloCreateUser');


    Route::put('/profile/update', 'Api\ProfileController@updateCustomer');
    Route::post('/profile/picture', 'Api\ProfileController@uploadPicture');
});



Route::prefix('/import')->middleware('nello.auth')->group(function(){
    Route::match(['post', 'put', 'delete'], '/users', 'ImportController@importUser');
    Route::post('/health-centers', 'ImportController@importHealthCenter');
    Route::post('/encounter', 'ImportController@importEncounter');
    Route::post('/health-tip', 'ImportController@importHealthTip');
//    Route::post('/investigation', 'ImportController@importInvestigation');
    Route::post('/invoice', 'ImportController@importInvoice');
    Route::post('/medication', 'ImportController@importMedication');
    Route::post('/payment', 'ImportController@importPayment');
//    Route::post('/procedure', 'ImportController@importProcedure');
    Route::post('/vital', 'ImportController@importVital');

    Route::match(['post', 'put', 'delete'], '/appointments', 'ImportController@importAppointment');
    Route::match(['post', 'put', 'delete'], '/investigations', 'ImportController@importInvestigation');
    Route::match(['post', 'put', 'delete'], '/procedures', 'ImportController@importProcedure');
    Route::match(['post', 'put', 'delete'], '/medical_history', 'ImportController@importMedicalHistory');

});


Route::get('/test/{id}/see', function(){
    return response(['hey']);
})->middleware('api.cache');


Route::prefix('/blogs')->group(function(){
    Route::get('/', 'Api\BlogController@index');
    Route::get('/show/{slug}', 'Api\BlogController@show');
    Route::post('/create', 'Api\BlogController@create')->middleware('jwt.auth');
    Route::post('/update', 'Api\BlogController@update')->middleware('jwt.auth');
    Route::delete('/{id}/delete', 'Api\BlogController@delete')->middleware('jwt.auth');
});

