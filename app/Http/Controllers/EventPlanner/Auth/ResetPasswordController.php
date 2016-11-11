<?php

namespace App\Http\Controllers\EventPlanner\Auth;

use App\Http\Controllers\Auth\ResetPasswordController as SiteResetPasswordController;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends SiteResetPasswordController
{

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/event-planner';

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
     * @return PasswordBroker
     */
    protected function broker()
    {
    	return Password::broker('event-planner');
    }
}
