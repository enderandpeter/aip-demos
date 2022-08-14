<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
	return view('index');
});

Route::get('search-my-backyard', function () {
	return view('search-my-backyard');
});

Route::post('search-my-backyard', [ LocationDataController::class, 'postLocation']);

Route::get('frogger', function () {
	return view('frogger');
});

Route::get('jack-the-giant', function () {
    return view('jack-the-giant');
});

Route::get('flappy-bird', function () {
    return view('flappy-bird');
});

Route::get('jack-the-giant-player', function () {
    return view('jack-the-giant-player');
});

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
