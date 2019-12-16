<?php declare(strict_types=1);

namespace My\Pages;

use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class EncounterPage extends AbstractPage
{
    const USER_NAME = "a.sidebar-info__name";
    const SIDEBAR_MENU = "div.sidebar__scroll-content";
    const LOGOUT_BUTTON = "div.sidebar-info__signout";
    const SIDEBAR_USER_INFO = "div.sidebar-info__user";

    const ALLERT_LOGOUT_ACCEPT_BUTTON = "div.js-signout-immediately";
    const ALLERT_LOGOUT_DISMISS_BUTTON = "div.js-ovl-close";

    //todo make page_title more flexible for different languages
    const PAGE_TITLE = 'Badoo — Знакомства';

    public function isEncounterPageLoad(): bool
    {
        try {
            $this->wd->wait(5)->until(WebDriverExpectedCondition::titleIs(self::PAGE_TITLE), 'Failed to load page');
        } catch (TimeoutException $e) {
            return false;
        }

        return true;
    }

    public function acceptLogOutAllert()
    {
        $this->wd->wait()->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::cssSelector(self::ALLERT_LOGOUT_ACCEPT_BUTTON)),
            'It is no confirm button in allert');
        $this->findElementByCss(self::ALLERT_LOGOUT_ACCEPT_BUTTON,'It is no confirm button in allert')->click();
    }

    public function dismissLogOutAllert()
    {
        $this->wd->wait()->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::cssSelector(self::ALLERT_LOGOUT_ACCEPT_BUTTON)),
            'It is no dismiss button in allert');
        $this->findElementByCss(self::ALLERT_LOGOUT_DISMISS_BUTTON,'It is no dismiss button in allert')->click();
    }

    public function getUserName(): string
    {
        $this->openSideBar();

        return $this->findElementByCss(self::USER_NAME,'It no User name specified in sidebar')->getText();
    }

    public function logOutButtonClick()
    {
        $this->openSideBar();

        $action = $this->wd->action();
        $UserInfo = $this->findElementByCss(self::SIDEBAR_USER_INFO,'');
        $action->moveToElement($UserInfo)->perform();

        $this->wd->wait()->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::cssSelector(self::LOGOUT_BUTTON)),
            'It is no logout button');
        $this->findElementByCss(self::LOGOUT_BUTTON,'It is no logout button')->click();
    }

    public function openSideBar()
    {
        $action = $this->wd->action();
        $sideBar = $this->findElementByCss(self::SIDEBAR_MENU,'It is no Sidebar element on Encounter page');
        $action->moveToElement($sideBar)->perform();
    }
}
