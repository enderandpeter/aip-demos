<?php

namespace App\EventPlanner;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

/**
 * Events scheduled on the user's calendar
 * 
 * @property Carbon $start_date The beginning of the event
 * @property Carbon $end_date The end of the event
 * 
 * @method CalendarEvent all() Get all the user's calendar events
 * 
 * @author Spencer
 *
 */
class CalendarEvent extends Model
{
    protected $table = 'eventplanner_calendarevents';
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [ 'id', 'created_at', 'updated_at' ];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'start_date', 'end_date' ];
    
    public static $date_format = 'n/j/Y g:i a';
    public static $datepicker_format = 'm/d/Y';
    
    /**
     * Get the user that owns the comment.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo( 'App\EventPlanner\User' );
    }
    
    public function showStartDate(){
    	return $this->start_date->format( self::$date_format );
    }
    
    public function showEndDate(){
    	return $this->end_date->format( self::$date_format );
    }
    
    public function editStartDate(){
    	return $this->start_date->format( self::$date_format );
    }
    
    public function editEndDate(){
    	return $this->end_date->format( self::$date_format );
    }
    
    public function getStartTime(){
    	return $this->start_date->format( 'g:i a' );
    }
    
    /**
     * Get user's calendar events by year and month
     * 
     * @param int $year
     * @param int $month
     * @return array
     */
    public static function getEventsByYearAndMonth( $year, $month ){
    	$events = [];
    	foreach( CalendarEvent::all() as $calendarEvent ){
    		if( $calendarEvent->start_date->year === $year && $calendarEvent->start_date->month === $month ){
    			$events[ $calendarEvent->start_date->day ][] = $calendarEvent;
    		}
    	}
    	
    	return $events;
    }
}
