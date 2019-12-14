<?php
/**
 * Created by PhpStorm.
 * User: stislavm
 * Date: 08/12/2019
 * Time: 22:47
 */

namespace My\Pages;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class BasePage extends AbstractPage
{
    //Locators on base page
    const SINGUP_BUTTON_SELECTOR = 'a.js-signup-link';
    const SINGIN_BUTTON_SELECTOR = 'a.js-signin-link';
    const FACEBOOK_BUTTON_SELECTOR = 'a.js-auth-button[data-external-provider="facebook"]';
    const VK_BUTTON_SELECTOR = 'a.js-auth-button[data-external-provider="vk"]';
    const SINGIN_TITLE_SELECTOR = '.sign-flow__title';
    const LANGUAGE_SELECTOR = ".language-selector__label";

    public function useFacebookAuthorization($email, $pass)
    {
        $this->debug('Try to Authorize with Facebook');

        $curentHandle = $this->wd->getWindowHandle();
        $windowHandlesBefore = $this->wd->getWindowHandles();

        $this->findByCss(self::FACEBOOK_BUTTON_SELECTOR)->click();

        $windowHandlesAfter = $this->wd->getWindowHandles();
        $newWindowHandle = array_diff($windowHandlesAfter, $windowHandlesBefore);

        $this->wd->switchTo()->window(reset($newWindowHandle));

        $this->FacebookAuthorization($email, $pass);

        $this->wd->switchTo()->window($curentHandle);
    }

    public function goToSingInPage()
    {
        $this->debug('Go to SingIn Page');
        $this->findByCss(self::SINGIN_BUTTON_SELECTOR)->click();
    }

    public function changeLanguage($language)
    {
        $this->debug('Try to change language');
        $languageSelector = $this->findByCss(self::LANGUAGE_SELECTOR);

        $action = $this->wd->action();
        $action->moveToElement($languageSelector)->perform();
        $action->moveToElement($this->wd->wait(3)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::linkText($language))))
            ->click()
            ->perform();
    }

    public function getSinginTitleText(): string
    {
        $this->debug('Get Singin Title ');
        $text = $this->findByCss(BasePage::SINGIN_TITLE_SELECTOR)->getText();

        return $text;
    }
}