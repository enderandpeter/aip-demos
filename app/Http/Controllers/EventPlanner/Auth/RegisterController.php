<?php

namespace App\Http\Controllers\EventPlanner\Auth;

use App\EventPlanner\User;
use App\Http\Controllers\Auth\RegisterController as SiteRegisterController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends SiteRegisterController
{
    /**
     * The authentication guard that should be used.
     *
     */
    protected function guard()
    {
    	return Auth::guard('eventplanner');
    }
    
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->redirectTo = route('event-planner');
    }

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
				'password' => 'required|min:6|max:255|confirmed',
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
}
