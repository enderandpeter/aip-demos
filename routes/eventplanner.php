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

Route::get('event-planner', [
	'uses' => 'CalendarEventController@index',
	'as' => 'event-planner'
]);

Route::group(['as' => 'event-planner.', 'prefix' => 'event-planner'], function() {
	Route::resource('events', 'CalendarEventController');

	/* TODO: Put these in a single function call, as in the style of Auth::routes() */
	// Authentication Routes...
	Route::get('login', [
			'uses' => 'Auth\LoginController@showLoginForm',
			'as' => 'login.show'
	]);
	Route::post('login', [
			'uses' => 'Auth\LoginController@login',
			'as' => 'login.post'
	]);
	Route::post('logout', [
			'uses' => 'Auth\LoginController@logout',
			'as' => 'logout'
	]);

	// Registration Routes...
	Route::get('register', [
			'uses' =>	'Auth\RegisterController@showRegistrationForm',
			'as' => 'register.show'
	]);
	Route::post('register', [
			'uses' => 'Auth\RegisterController@register',
			'as' => 'register.post'
	]);

	// Password Reset Routes...
	Route::get('password/reset/{token?}', [
			'uses' => 'Auth\ResetPasswordController@showResetForm',
			'as' => 'password-reset.show'
	]);
	Route::post('password/email', [
			'uses' => 'Auth\ResetPasswordController@sendResetLinkEmail',
			'as' => 'password-reset.email'
	]);
	Route::post('password/reset', [
			'uses' => 'Auth\ResetPasswordController@reset',
			'as' => 'password-reset.post'
	]);
});