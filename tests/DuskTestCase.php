<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Exception;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Illuminate\Support\Str;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function setUpBeforeClass(): void
    {
        if(self::getOSEnvironment() == 'Homestead'){
            exec('Xvfb -ac :0 -screen 0 1280x1024x16 &', $output, $returnValue);
            $returnValue = intval($returnValue);

            if($returnValue !== 0){
                throw new Exception('Could not start Xvfb');
            }
        }
    }

    public static function tearDownAfterClass(): void
    {
        if(self::getOSEnvironment() == 'Homestead'){
            $pids_Xvfb = exec('pidof Xvfb');
            if(!empty($pids_Xvfb)){
                exec("kill -9 $pids_Xvfb");
            }
        }
    }

    /**
     * @return string
     */
    private static function getOSEnvironment()
    {
        if(PHP_OS === 'Linux'){
            $kernelName = exec('uname -r');
            $osInfo = exec('lsb_release -r');

            if(Str::contains($kernelName, ['moby', 'linuxkit', 'microsoft-standard']) /* Docker */){
                return 'Docker';
            }

            if(Str::contains($osInfo, 'trusty')  /* TravisCI */){
                return 'TravisCI';
            }

            if(Str::contains($osInfo, '16.04')  /* Homestead */){
                return 'Homestead';
            }
        }

        return PHP_OS;
    }

    private static function isTextEnvironment()
    {
        return self::getOSEnvironment() == 'Docker' ||
            self::getOSEnvironment() == 'TravisCI' ||
            self::getOSEnvironment() == 'Homestead';
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
     * @return RemoteWebDriver
     */
    protected function driver()
    {
        $defaultSettings = [];
        $arguments = [];

        if(self::isTextEnvironment()){
            $arguments = array_merge($defaultSettings, [
                '--disable-gpu',
                '--headless',
                '--no-sandbox',
            ]);
        }

        $options = (new ChromeOptions)->addArguments($arguments);

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
                )->setCapability('acceptInsecureCerts', true));
    }
}
