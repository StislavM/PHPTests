<?php declare(strict_types=1);

namespace My\Pages;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Lmc\Steward\Component\AbstractComponent;

abstract class AbstractPage extends AbstractComponent
{
    const FACEBOOK_EMAIL_FIELD = '#email';
    const FACEBOOK_PASS_FIELD = '#pass';
    const FACEBOOK_LOGIN_BUTTON = '[name="login"]';
    const FACEBOOK_CONFIRM_BUTTON = 'button[name="__CONFIRM__"]';

    /**
     * Locates element matching a CSS selector.
     *
     * @param string $cssSelector CSS selector to be located.
     * @param string $message exception message.
     * @throws \RuntimeException
     * @return RemoteWebElement The first element located using the mechanism. Exception is thrown if no
     * element found.
     */
    public function findElementByCss(string $cssSelector, string $message): RemoteWebElement
    {
        $message = $message . ". Failed to find element by css selector '" . $cssSelector . "'";
        try {
            $element = $this->findByCss($cssSelector);
        } catch (NoSuchElementException $exception) {
            throw new \RuntimeException($message);
        }

        return $element;
    }

    public function FacebookAuthorization(string $email, string $pass)
    {
        $this->findElementByCss(self::FACEBOOK_EMAIL_FIELD,
            "Email field is not found on Facebook authorization window")->sendKeys($email);
        $this->findElementByCss(self::FACEBOOK_PASS_FIELD,
            "Pass field is not found on Facebook authorization window")->sendKeys($pass);
        $this->findElementByCss(self::FACEBOOK_LOGIN_BUTTON,
            "Login button  is not found on Facebook authorization window")->click();
    }

}