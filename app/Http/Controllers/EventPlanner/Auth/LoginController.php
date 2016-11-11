<?php

namespace App\Http\Controllers\EventPlanner\Auth;

use App\Http\Controllers\Auth\LoginController as SiteLoginController;

use Illuminate\Support\Facades\Auth;

class LoginController extends SiteLoginController
{

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest', ['except' => 'logout']);
    }
}
