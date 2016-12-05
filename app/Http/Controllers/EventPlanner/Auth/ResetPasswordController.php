<?php

namespace App\Http\Controllers\EventPlanner\Auth;

use App\Http\Controllers\Auth\ResetPasswordController as SiteResetPasswordController;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends SiteResetPasswordController
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
     * Where to redirect users after resetting their password.
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
     * Get the broker to be used during password reset.
     *
     * @return PasswordBroker
     */
    public function broker()
    {
    	return Password::broker('eventplanner_users');
    }
}
