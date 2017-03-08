<?php

namespace Tests\Browser\EventPlanner;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FrontendTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicFrontend()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit( route( 'event-planner' ) )
                    ->assertSee('Welcome to Event Planner!');
        });
    }
}
