<?php

namespace App\Http\Controllers\Auth\EventPlanner;

use Illuminate\Http\Request;

use App\Http\Controllers\Auth\AuthController as SiteAuthController;

use App\EventPlanner\User;

use Validator;
use Auth;

class AuthController extends SiteAuthController
{	
	/**
	 * The authentication guard that should be used.
	 *
	 * @var string
	 */
	protected $guard = 'eventplanner';
	
	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/event-planner';
	
	/**
	 * Where to redirect users after logout.
	 *
	 * @var string
	 */
	protected $redirectAfterLogout = '/event-planner';

	
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
				'name' => 'required|max:255',				
				'password' => 'required|min:6|confirmed',
				'email' => 'required|email|max:255|unique:eventplanner_users'
		]);
	}
	
	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function create(array $data)
	{
		return User::create([
				'name' => $data['name'],
				'email' => $data['email'],
				'password' => bcrypt($data['password']),
		]);
	}
	
	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request)
	{
		$validator = $this->validator($request->all());
	
		if ($validator->fails()) {
			$this->throwValidationException(
					$request, $validator
					);
		}
	
		$remember = true;
		$requestData = $request->all();
		$newUserData = $this->create($request->all());
		Auth::guard($this->getGuard())->attempt(['email' => $newUserData->email, 'password' => $requestData['password']], $remember);
	
		return redirect($this->redirectPath());
	}
}