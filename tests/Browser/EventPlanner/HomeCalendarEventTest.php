<?php

namespace Tests\Browser\EventPlanner;

use App\Models\EventPlanner\CalendarEvent;
use App\Models\EventPlanner\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;

class HomeCalendarEventTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Check that a user's calendar events are shown on the right calendar on the home page
     *
     * @group home-show-event
     * @group loginas
     *
     * @return void
     *
     * @throws Throwable
     */
    public function test_home_event_listing()
    {
        $this->browse(function (Browser $browser) {
            $caldendarEvents = CalendarEvent::factory()->count(5)->create();
            $user = User::find($caldendarEvents[0]->user_id);

            $browser->loginAs($user, 'eventplanner');

            foreach ($caldendarEvents as $calendarEvent) {
                $browser->visit(route('event-planner', [
                    'year' => $calendarEvent->start_date->year,
                    'month' => $calendarEvent->start_date->month,
                    'day' => $calendarEvent->start_date->day,
                    'submit' => 1,
                ]))
                    ->assertSeeIn('.calendar-day-'.$calendarEvent->start_date->day, $calendarEvent->name)
                    ->assertSeeIn('.calendar-day-'.$calendarEvent->start_date->day, $calendarEvent->getStartTime());
            }
        });
    }
}
