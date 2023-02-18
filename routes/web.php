<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LocationDataController;


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
    return view('index');
});

Route::get('search-my-backyard', function () {
    Inertia::setRootView('search-my-backyard');
    return Inertia::render('SearchMyBackyard');
});

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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
