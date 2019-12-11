<?php

namespace My\Pages;

use Lmc\Steward\Component\AbstractComponent;

// This class is used for adding methods familiar for all pages
abstract class AbstractPage extends AbstractComponent
{
    const FACEBOOK_EMAIL_FIELD = '#email';
    const FACEBOOK_PASS_FIELD = '#pass';
    const FACEBOOK_LOGIN_BUTTON = '[name="login"]';
    const FACEBOOK_CONFIRM_BUTTON= 'button[name="__CONFIRM__"]';

    public function FacebookAuthorization($email, $pass)
    {
        $this->findByCss(self::FACEBOOK_EMAIL_FIELD)->sendKeys($email);
        $this->findByCss(self::FACEBOOK_PASS_FIELD)->sendKeys($pass);
        $this->findByCss(self::FACEBOOK_LOGIN_BUTTON)->click();
    }

}