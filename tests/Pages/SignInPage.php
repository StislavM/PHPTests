<?php

namespace My\Pages;

use Facebook\WebDriver\Exception\NoSuchElementException;
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

        try {
            $this->findByCss(self::FACEBOOK_BUTTON_SELECTOR)->click();
        } catch (NoSuchElementException $e) {
            $this->warn('It is no Facebook Authotization button');
        }

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
        $this->debug("Fill Email Field");
        $this->findByCss(self::EMAIL_FIELD)->sendKeys($email);
    }

    public function fillPassField($pass)
    {
        $this->debug("Fill Password Field");
        $this->findByCss(self::PASS_FIELD)->sendKeys($pass);
    }

    public function clickSignInButton()
    {
        $this->debug("Click Signin Button");
        $this->findByCss(self::SIGNIN_BUTTON)->click();
    }

    public function SelectRememberMeCheckbox()
    {
        $this->debug("Selecting Remember me checkbox");
        try {
            $checkbox = $this->findByCss(self::REMEMBER_ME_FIELD);
            if (!($checkbox->isSelected())) {
                $checkbox->click();
            }
        } catch (NoSuchElementException $e) {
            $this->warn("It is no remember me checkbox");
        }
    }

    public function ClearRememberMeCheckbox()
    {
        $this->debug("Clear Remember me checkbox");
        try {
            $checkbox = $this->findByCss(self::REMEMBER_ME_FIELD);
            if ($checkbox->isSelected()) {
                $checkbox->clear();
            }
        } catch (NoSuchElementException $e) {
            $this->warn("It is no remember me checkbox");
        }
    }

    public function getFormErrorMessage(): string
    {
        $this->wd->wait(10)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::cssSelector(self::FORM_ERRORS)),'It is no failed auth ');
        $errors = $this->findMultipleByCss(self::FORM_ERRORS);

        return $errors[0]->getText();
    }

    public function getValueAttributeOfEmailInput(): string
    {
        return $this->findByCss(SignInPage::EMAIL_FIELD)->getAttribute('value');
    }
}