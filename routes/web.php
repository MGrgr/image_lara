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
    return redirect('feed');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('profile')->middleware(['auth','verified'])->group(function () {
    Route::get('/', 'UserController@index');
    Route::get('/{id}', 'UserController@show');
});
Route::resource('images', 'ImagesController')->except([
    'index','my','update'
]);

//Laravel bug - cannot read multipart-form-data for PUT/PATCH, override with POST
Route::post('images/{image}','ImagesController@update')->name('images.update')->middleware(['auth','verified']);
Route::get('/feed', 'ImagesController@index')->name('feed');
Route::get('/my','ImagesController@my')->name('my')->middleware(['auth','verified']);