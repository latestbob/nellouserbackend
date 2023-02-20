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

// Route::get('/cart/{uuid}','FakeController@getcart');

// Route::get('/fake/cart', 'FakeController@getfakecart');

\Mpociot\ApiDoc\ApiDoc::routes("/apidoc");