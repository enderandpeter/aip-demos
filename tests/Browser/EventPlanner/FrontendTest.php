<?php

namespace Tests\Browser\EventPlanner;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Throwable;

class FrontendTest extends DuskTestCase
{
	use DatabaseMigrations;

    /**
     * A basic browser test example.
     *
     * @return void
     * @throws Throwable
     */
    public function testBasicFrontend()
    {
        $this->browse(function ( Browser $browser ) {
            $browser->visit( route( 'event-planner' ) )
                    ->assertSee( 'Welcome to Event Planner!' );
        });
    }
}
