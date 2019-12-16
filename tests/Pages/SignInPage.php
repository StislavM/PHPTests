<?php declare(strict_types=1);

namespace My\Pages;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class SignInPage extends AbstractPage
{
    const FACEBOOK_BUTTON_SELECTOR = 'a.js-fbsession';
    const EMAIL_FIELD = 'input[name="email"]';
    const PASS_FIELD = 'input[name="password"]';
    const REMEMBER_ME_FIELD = 'input[name="remember"]';
    const SIGNIN_BUTTON = 'button[type="submit"]';
    const FORM_ERRORS = "[data-qa-role='form-error']";

    public function useFacebookAuthorization($email, $pass)
    {
        $this->debug('Try to Authorize with Facebook');

        $curentHandle = $this->wd->getWindowHandle();
        $windowHandlesBefore = $this->wd->getWindowHandles();

        $this->findElementByCss(self::FACEBOOK_BUTTON_SELECTOR,'Cannot find Facebook authorization button')->click();

        $windowHandlesAfter = $this->wd->getWindowHandles();
        $newWindowHandle = array_diff($windowHandlesAfter, $windowHandlesBefore);

        $this->wd->switchTo()->window(reset($newWindowHandle));

        $this->FacebookAuthorization($email, $pass);
        $this->wd->switchTo()->window($curentHandle);
    }

    public function SingIn($email, $pass)
    {
        $this->fillEmailField($email);
        $this->fillPassField($pass);
        $this->clickSignInButton();
    }

    public function fillEmailField($email)
    {
        $this->log("Fill Email field");
        $this->findElementByCss(self::EMAIL_FIELD,'Cannot find Email input field')->sendKeys($email);
    }

    public function fillPassField($pass)
    {
        $this->log("Fill Password field");
        $this->findElementByCss(self::PASS_FIELD,'Cannot find Pass input field')->sendKeys($pass);
    }

    public function clickSignInButton()
    {
        $this->log("Click Signin Button");
        $this->findElementByCss(self::SIGNIN_BUTTON,'Cannot find Signin button')->click();
    }

    public function SelectRememberMeCheckbox()
    {
        $this->log("Selecting Remember me checkbox");
        $checkbox = $this->findElementByCss(self::REMEMBER_ME_FIELD,'Cannot find Remember me checkbox');
        if (!($checkbox->isSelected())) {
            $checkbox->click();
        }
    }

    public function ClearRememberMeCheckbox()
    {
        $this->log("Clear Remember me checkbox");

        $checkbox = $this->findElementByCss(self::REMEMBER_ME_FIELD,'Cannot find Remember me checkbox');
        if ($checkbox->isSelected()) {
            $checkbox->clear();
        }
    }

    public function getFormErrorMessage(): string
    {
        $this->log("Get first form error message text");

        $this->wd->wait(5)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::cssSelector(self::FORM_ERRORS)),
            'Cannot find form error message text');
        $error = $this->findElementByCss(self::FORM_ERRORS,'Cannot find form error message text');

        return $error->getText();
    }

    public function getValueAttributeOfEmailInput(): string
    {
        return $this->findElementByCss(SignInPage::EMAIL_FIELD,'Cannot find Email input field')->getAttribute('value');
    }
}