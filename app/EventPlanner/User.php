<?php

namespace App\EventPlanner;

use App\User as SiteUser;

class User extends SiteUser{	
	/**
	 * Get all calendar events for this user
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function calendarEvents()
	{
		return $this->hasMany('App\CalendarEvents');
	}
}