<?php

namespace App\Http\Controllers\EventPlanner\Auth;

use App\Models\EventPlanner\User;
use App\Http\Controllers\Auth\RegisterController as SiteRegisterController;
use App\Models\EventPlanner\ValidationData;
use App\Http\Controllers\EventPlanner\ValidatesEventPlannerRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends SiteRegisterController
{
	use ValidatesEventPlannerRequests;

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
        $this->redirectTo = route( 'event-planner' );
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
    	/*
    	 * Get the validation messages for each input in the register form so they can be loaded and
    	 * shown by the frontend validation.
    	 */
    	$messages = $this->getValidationMessagesArray('register');

    	$viewData = [
    			'validationMessages' => json_encode( $messages )
    	];
    	return view('auth.register', $viewData);
    }

    /**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		$messages = [
			'regex' => trans('validation.specialchars')
		];
		$validationData = new ValidationData();
		return Validator::make($data, $validationData->getData('register'), $messages);
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
