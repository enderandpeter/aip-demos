<?php

namespace App\Http\Controllers\EventPlanner\Auth;

use App\Http\Controllers\Auth\LoginController as SiteLoginController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class LoginController extends SiteLoginController
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
	 * Log the user out of the application.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function logout(Request $request)
	{
		$this->guard()->logout();
	
		$request->session()->flush();
	
		$request->session()->regenerate();
	
		return redirect($this->redirectTo);
	}
	
    /**
     * Where to redirect users after login.
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
        $this->middleware('guest', ['except' => 'logout']);
        $this->redirectTo = route('event-planner');
    }
}
