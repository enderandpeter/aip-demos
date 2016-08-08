<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
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


Route::group(array('prefix' => 'event-planner'), function() {
	Route::resource('events', 'CalendarEventController');
	
	/* TODO: Put these in a single function call, as in the style of Route::auth() */
	// Authentication Routes...
	Route::get('login', [
			'uses' => 'Auth\EventPlanner\AuthController@showLoginForm',
			'as' => 'event-planner.login.show'
	]);
	Route::post('login', [
			'uses' => 'Auth\EventPlanner\AuthController@login',
			'as' => 'event-planner.login.post'
	]);
	Route::get('logout', [
			'uses' => 'Auth\EventPlanner\AuthController@logout',
			'as' => 'event-planner.logout'
	]);
	
	// Registration Routes...
	Route::get('register', [
			'uses' =>	'Auth\EventPlanner\AuthController@showRegistrationForm',
			'as' => 'event-planner.register.show'
	]);
	Route::post('register', [
			'uses' => 'Auth\EventPlanner\AuthController@register',
			'as' => 'event-planner.register.post'
	]);
	
	// Password Reset Routes...
	Route::get('password/reset/{token?}', [
			'uses' => 'Auth\EventPlanner\PasswordController@showResetForm',
			'as' => 'event-planner.password-reset.show'
	]);
	Route::post('password/email', [
			'uses' => 'Auth\EventPlanner\PasswordController@sendResetLinkEmail',
			'as' => 'event-planner.password-reset.email'
	]);
	Route::post('password/reset', [
			'uses' => 'Auth\EventPlanner\PasswordController@reset',
			'as' => 'event-planner.password-reset.post'
	]);
});
