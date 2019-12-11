<?php

namespace My;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Lmc\Steward\ConfigProvider;

/**
 * Abstract class for custom tests, could eg. define some properties or instantiate some common components
 * using @before annotated methods.
 */
abstract class AbstractTestCase extends \Lmc\Steward\Test\AbstractTestCase
{
    /** @var int */
    public const BROWSER_WIDTH = 1024;
    /** @var int */
    public const BROWSER_HEIGHT = 768;
    /** @var string */
    public static $baseUrl;

    /**
     * @before
     */
    public function initBaseUrl()
    {
        // Set base url according to environment
        switch (ConfigProvider::getInstance()->env) {
            case 'production':
                self::$baseUrl = 'https://badoo.com';
                break;
            case 'staging':
                self::$baseUrl = '*****'; //todo specify staging url
                break;
            default:
                throw new \RuntimeException(sprintf('Unknown environment "%s"', ConfigProvider::getInstance()->env));
        }

        $this->debug('Base URL set to "%s"', self::$baseUrl);

        if (ConfigProvider::getInstance()->env === 'production') {
            $this->warn('The tests are run against production, so be careful!');
        }

        //Using handless mode for Chrome if it is needed
//        if (ConfigProvider::getInstance()->browserName == WebDriverBrowserType::CHROME) {
//            $options = new ChromeOptions();
//            $options->addArguments(['--headless', 'window-size=1024,768', '--no-sandbox']);
//            $capabilities = DesiredCapabilities::chrome();
//            $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
//            $url = 'http://localhost:4444/wd/hub';
//            $this->wd = RemoteWebDriver::create($url, $capabilities);
//        }
    }

    /**
     * @after
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->wd->quit();
    }
}
