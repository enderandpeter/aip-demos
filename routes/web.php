<?php

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

Route::post('search-my-backyard', ['uses' => 'LocationDataController@postLocation', 'as' => 'postLocation']);

Route::get('frogger', function () {
	return view('frogger');
});

Route::get('event-planner', [
		'uses' => 'CalendarEventController@index',
		'as' => 'event-planner'
]);

Route::group(['as' => 'event-planner.', 'prefix' => 'event-planner'], function() {
	Route::resource('events', 'CalendarEventController');

	/* TODO: Put these in a single function call, as in the style of Auth::routes() */
	// Authentication Routes...
	Route::get('login', [
			'uses' => 'Auth\EventPlanner\LoginController@showLoginForm',
			'as' => 'event-planner.login.show'
	]);
	Route::post('login', [
			'uses' => 'Auth\EventPlanner\LoginController@login',
			'as' => 'event-planner.login.post'
	]);
	Route::post('logout', [
			'uses' => 'Auth\EventPlanner\LoginController@logout',
			'as' => 'event-planner.logout'
	]);

	// Registration Routes...
	Route::get('register', [
			'uses' =>	'Auth\EventPlanner\RegisterController@showRegistrationForm',
			'as' => 'event-planner.register.show'
	]);
	Route::post('register', [
			'uses' => 'Auth\EventPlanner\RegisterController@register',
			'as' => 'event-planner.register.post'
	]);

	// Password Reset Routes...
	Route::get('password/reset/{token?}', [
			'uses' => 'Auth\EventPlanner\ResetPasswordController@showResetForm',
			'as' => 'event-planner.password-reset.show'
	]);
	Route::post('password/email', [
			'uses' => 'Auth\EventPlanner\ResetPasswordController@sendResetLinkEmail',
			'as' => 'event-planner.password-reset.email'
	]);
	Route::post('password/reset', [
			'uses' => 'Auth\EventPlanner\ResetPasswordController@reset',
			'as' => 'event-planner.password-reset.post'
	]);
});
