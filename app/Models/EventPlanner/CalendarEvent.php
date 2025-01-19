<?php

namespace App\Models\EventPlanner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    use HasFactory;

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
    protected array $dates = [ 'start_date', 'end_date' ];

    protected function casts(): array {
        return [
            'start_date' => 'datetime:'.self::$date_format,
            'end_date' => 'datetime:'.self::$date_format,
        ];
    }

    public static string $date_format = 'n/j/Y g:i a';
    public static string $datepicker_format = 'm/d/Y';

    /**
     * Get the user that owns the comment.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
    	return $this->belongsTo(User::class);
    }

    public function showStartDate(): string
    {
    	return $this->start_date->format( self::$date_format );
    }

    public function showEndDate(): string
    {
    	return $this->end_date->format( self::$date_format );
    }

    public function editStartDate(): string
    {
    	return $this->start_date->format( self::$date_format );
    }

    public function editEndDate(): string
    {
    	return $this->end_date->format( self::$date_format );
    }

    public function getStartTime(): string
    {
    	return $this->start_date->format( 'g:i a' );
    }

    /**
     * Get user's calendar events by year and month
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public static function getEventsByYearAndMonth(int $year, int $month ): array
    {
    	$events = [];
    	foreach( CalendarEvent::all() as $calendarEvent ){
    		if( $calendarEvent->start_date->year === $year && $calendarEvent->start_date->month === $month ){
    			$events[ $calendarEvent->start_date->day ][] = $calendarEvent;
    		}
    	}

    	return $events;
    }
}
