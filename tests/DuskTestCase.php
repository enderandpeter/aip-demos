<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use App\Testing\InitialiseDatabaseTrait;

abstract class DuskTestCase extends BaseTestCase
{
	use CreatesApplication, InitialiseDatabaseTrait;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()
        );
    }
    
    public function setUpTraits()
    {
    	$this->backupDatabase();
    	parent::setUpTraits();
    }
    
    public static function tearDownAfterClass(){
    	if( PHP_OS === 'Linux' ){
    		$pids_chromium = exec( 'pidof chromium' );
    		$pids_Xvfb = exec( 'pidof Xvfb' );
    		
    		if( !empty( $pids_chromium ) ){
    			exec( "kill -9 $pids_chromium" );
    		}
    		
    		if( !empty( $pids_Xvfb ) ){
    			exec( "kill -9 $pids_Xvfb" );
    		}
    	}
    }
}
