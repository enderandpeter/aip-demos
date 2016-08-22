<?php

namespace App\Http\Controllers\Auth\EventPlanner;

use App\Http\Controllers\Auth\PasswordController as SitePasswordController;

class PasswordController extends SitePasswordController{
	/**
	 * The authentication guard that should be used.
	 *
	 * @var string
	 */
	protected $guard = 'eventplanner';
	
	/**
	 * The password broker that should be used.
	 *
	 * @var string
	 */
	protected $broker = 'eventplanner';
}