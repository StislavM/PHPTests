<?php declare(strict_types=1);

namespace My\Pages;

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

    public function useFacebookAuthorization(string $email, string $pass)
    {
        $this->log('Try to Authorize with Facebook');

        $curentHandle = $this->wd->getWindowHandle();
        $windowHandlesBefore = $this->wd->getWindowHandles();

        $this->findElementByCss(self::FACEBOOK_BUTTON_SELECTOR,
            'Facebook Authorization button is not found on main page')->click();

        $windowHandlesAfter = $this->wd->getWindowHandles();
        $newWindowHandle = array_diff($windowHandlesAfter, $windowHandlesBefore);

        $this->wd->switchTo()->window(reset($newWindowHandle));

        $this->FacebookAuthorization($email, $pass);

        $this->wd->switchTo()->window($curentHandle);
    }

    public function goToSingInPage()
    {
        $this->log('Go to SingIn Page');
        $this->findElementByCss(self::SINGIN_BUTTON_SELECTOR, 'Cannot find link to SingIn page')->click();
    }

    public function changeLanguage(string $language)
    {
        $this->log('Try to change language');
        $languageSelector = $this->findElementByCss(self::LANGUAGE_SELECTOR, 'Cannot find language selector on main page');

        $action = $this->wd->action();
        $action->moveToElement($languageSelector)->perform();
        $action->moveToElement($this->wd->wait(3)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::linkText($language)),sprintf('Cannot find "%s" option in dropdown menu',$language)))
            ->click()
            ->perform();
    }

    public function getSinginTitleText(): string
    {
        $this->log('Get Singin Title');
        $text = $this->findElementByCss(BasePage::SINGIN_TITLE_SELECTOR, 'Cannot find Singin Title text ')->getText();

        return $text;
    }
}