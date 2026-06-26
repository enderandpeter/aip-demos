<?php

namespace Tests\Browser\EventPlanner;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;

class FrontendTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A basic browser test example.
     *
     * @return void
     *
     * @throws Throwable
     */
    public function test_basic_frontend()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('event-planner'))
                ->assertSee('Welcome to Event Planner!');
        });
    }
}
