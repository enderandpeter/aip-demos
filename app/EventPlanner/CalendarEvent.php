<?php

namespace App\EventPlanner;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $table = 'eventplanner_calendarevents';
    
    /**
     * Get the post that owns the comment.
     */
    public function user()
    {
    	return $this->belongsTo('App\EventPlanner\User');
    }
}
