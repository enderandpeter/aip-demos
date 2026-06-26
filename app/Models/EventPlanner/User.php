<?php

namespace App\Models\EventPlanner;

use App\Models\User as SiteUser;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends SiteUser
{
    protected $table = 'eventplanner_users';

    /**
     * Get all calendar events for this user
     *
     * @return HasMany
     */
    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }
}
