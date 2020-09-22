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
Auth::routes();

Route::get('/', function () {
    return view('home');
});

Route::get('accounts', 'AccountController@index');

Route::get('accounts/{id}/edit', 'AccountController@edit');

Route::put('accounts/{id}', 'AccountController@update');

Route::delete('accounts/{id}', 'AccountController@destroy');

Route::get('accounts/1/certificate', function(){
    return view('accounts.certificate');
});

Route::get('accounts/1/parameters', function(){
    return view('accounts.parameters');
});

Route::get('partners', function(){
    return view('partners.index');
});

Route::get('partners/1', function(){
    return view('partners.edit');
});

Route::group(['prefix' => 'products'], function() {
    Route::get('/', 'ProductController@index');
    Route::post('/', 'ProductController@index');
    Route::get('/edit', 'ProductController@edit');
    Route::get('/edit/{id}', 'ProductController@edit');
    Route::get('/show/{id}', 'ProductController@show');
    Route::post('/update', 'ProductController@update');
    Route::get('/destroy/{id}', 'ProductController@destroy');
});

Route::get('imports', function(){
    return view('imports.index');
});

Route::get('backup', function(){
    return view('backup.index');
});

Route::get('help/help', function(){
    return view('help.help');
});

Route::get('help/status', function(){
    return view('help.status');
});

Route::get('nfes/create', function(){
    return view('nfes.create');
});

Route::get('nfes', function(){
    return view('nfes.index');
});

Route::get('nfes/1/cancel', function(){
    return view('nfes.cancel');
});

Route::get('nfes/consult', function(){
    return view('nfes.consult');
});

Route::get('nfes/inut', function(){
    return view('nfes.inutilization');
});

//Route::get('/home', 'HomeController@index')->name('home');
