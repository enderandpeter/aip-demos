<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Exception;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;
    
    public static function setUpBeforeClass()
    {
        if(self::isTextEnvironment()){
            exec('Xvfb -ac :0 -screen 0 1280x1024x16 &', $output, $returnValue);
            $returnValue = intval($returnValue);
            
            if($returnValue !== 0){
                throw new Exception('Could not start Xvfb');
            }
        }  
    }
    
    public static function tearDownAfterClass()
    {
        if(self::isTextEnvironment()){
            $pids_Xvfb = exec('pidof Xvfb');
            if(!empty($pids_Xvfb)){
                exec("kill -9 $pids_Xvfb");
            }
        }
    }
    
    /**
     * @return boolean
     */
    private static function isTextEnvironment()
    {
        if(PHP_OS === 'Linux'){
            $kernelName = exec('uname -r');
            $osInfo = exec('lsb_release -r');
        
            if(str_contains($kernelName, 'moby') /* Docker */ ||
                str_contains($osInfo, 'trusty') /* TravisCI */){
                
                return true;
            }
        }
        
        return false;
    }
    
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
        $defaultSettings = [];
        $arguments = [];
        
        if(self::isTextEnvironment()){
            $arguments = array_merge($defaultSettings, ['--disable-gpu', '--no-sandbox']);
        }
        
        $options = (new ChromeOptions)->addArguments($arguments);
        
        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
                ),
            150000, 150000);
    }
}
