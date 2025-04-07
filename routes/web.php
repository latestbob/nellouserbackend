<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/servicepay', function (){
    return view('servicepay');
});


Route::get('/owcservicepay', function (){
    return view('owcservicepay');
});


Route::get("/cart/{cart_uuid}","SalesReportController@cartlink")->name("cartlink");

Route::post('/cart/pay',"SalesReportController@cartpay")->name("cartpay");

Route::get('/payment/callback',"SalesReportController@paymentcallback")->name("paymentcallback");
// Route::get('/cart/{uuid}','FakeController@getcart');

// Route::get('/fake/cart', 'FakeController@getfakecart');

\Mpociot\ApiDoc\ApiDoc::routes("/apidoc");

Route::get("/order-complete","SalesReportController@ordercomplete")->name("thankyou");


Route::get("/outlook/{start}/{care}/{time}","AppointmentController@generateicsfile")->name("generateics");


Route::get("downloadics/{start}/{end}/{care}","AppointmentController@downloadics")->name("downloadics");