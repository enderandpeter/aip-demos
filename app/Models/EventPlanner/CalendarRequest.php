<?php

namespace App\Models\EventPlanner;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

/**
 * Handle managing data for Event Planner requests
 *
 * @author Spencer
 */
class CalendarRequest
{
    use ValidatesRequests;

    /**
     * Get the calendar data for the event planner request
     *
     * @param  Request  $request  The request object for this request
     * @param  Controller  $controller  The controller object handling the calendar request
     * @return mixed[]
     */
    public function getCalendarData(Request $request)
    {
        $currentDate = Carbon::now();

        /*
         * If a specific date is not being looked up, set to the current date
         */
        if (empty($request->input('submit'))) {
            $request->merge(['submit' => 'today']);
        }

        $month = $request->input('submit') !== 'today' && $request->input('month') ? $request->input('month') : $currentDate->month;
        $day = $request->input('submit') !== 'today' && $request->input('day') ? $request->input('day') : $currentDate->day;
        $year = $request->input('submit') !== 'today' && $request->input('year') ? $request->input('year') : $currentDate->year;
        $date = $month.' '.$day.' '.$year;

        if ($request->input('month') && $request->input('month') && $request->input('year') && ! session('errors')) {
            self::setInputs($request, $date, $month, $day, $year);

            $this->validate($request, [
                'date' => 'required|date_format:n j Y',
            ]);
        } elseif (! session('errors')) {
            self::setInputs($request, $date, $month, $day, $year);
        } else {
            $month = $currentDate->month;
            $year = $currentDate->year;
            $date = $month.' '.$day.' '.$year;
            self::setInputs($request, $date, $month, $day, $year);
        }

        $calendarDate = Carbon::createFromFormat('n j Y', $date);

        $calendarData = [
            'calendarHeading' => $calendarDate->format('F').' '.$calendarDate->format('Y'),
            'calendarDate' => $calendarDate,
            'currentDate' => $currentDate,
        ];

        return $calendarData;
    }

    /**
     * Set the expected request inputs
     *
     * @param  Request  $request  The request for which the inputs are being set
     * @param  string  $date  A date of the format 'n Y'
     * @param  int  $month  The month
     * @param  int  $year  The year
     */
    private static function setInputs(Request $request, $date, $month, $day, $year)
    {
        $request->replace([
            'date' => $request->input('date', $date),
            'month' => $month,
            'day' => $day,
            'year' => $year,
            'submit' => $request->input('submit'),
        ]);
    }
}
