<?php declare(strict_types=1);

namespace My\Steward;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Lmc\Steward\ConfigProvider;
use Lmc\Steward\Selenium\CustomCapabilitiesResolverInterface;
use Lmc\Steward\Test\AbstractTestCase;

class CustomCapabilitiesResolver implements CustomCapabilitiesResolverInterface
{
    /** @var ConfigProvider */
    private $config;

    public function __construct(ConfigProvider $config)
    {
        $this->config = $config;
    }

    public function resolveDesiredCapabilities(
        AbstractTestCase $test,
        DesiredCapabilities $capabilities
    ): DesiredCapabilities {

//      create names for test video
        if (strpos($this->config->capability, "enableVideo") !== false) {
            $capabilities->setCapability('videoName', $test->getName() . '.mp4');
        }

        return $capabilities;
    }

    public function resolveRequiredCapabilities(
        AbstractTestCase $test,
        DesiredCapabilities $capabilities
    ): DesiredCapabilities {
        return $capabilities;
    }
}