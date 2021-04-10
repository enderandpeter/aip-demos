<?php

namespace App\Http\Controllers\EventPlanner\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController as SiteForgetPasswordController;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends SiteForgetPasswordController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
    	return Password::broker('eventplanner_users');
    }
}
