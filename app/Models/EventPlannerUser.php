<?php

namespace App\Models;

class EventPlannerUser extends User{
	protected $table = 'eventplanner_users';

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
