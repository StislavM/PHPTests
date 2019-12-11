<?php

namespace My;

use My\Pages\BasePage;
use My\Pages\SignInPage;
use Facebook\WebDriver\WebDriverExpectedCondition;
use My\Pages\EncounterPage;
use My\Steward\UserCredits;
require_once __DIR__.'/../src/UsersCredits.php'; //change to autoloader

class SignInPageTest extends AbstractTestCase
{
    protected $singInPage;
    protected $userData;
    protected $additionalUrl = '/signin/';

    /**
     * @before
     */
    public function init()
    {
        $this->singInPage = new SignInPage($this);
        $signInUrl = self::$baseUrl . $this->additionalUrl;
        $this->wd->get($signInUrl);

        $this->userData=new UserCredits(UserCredits::REAL_USER);
    }

    public function testSuccessSignIn()
    {
        $this->singInPage->fillEmailField($this->userData->getEmail());
        $this->singInPage->fillPassField($this->userData->getPass());
        $this->singInPage->clickSignInButton();

        $encounterPage = new EncounterPage($this);
        $this->wd->wait(5)->until(WebDriverExpectedCondition::titleIs($encounterPage::PAGE_TITLE));

        $this->assertContains('encounters', $this->wd->getCurrentURL(),
            "After Authorization we get not into encounters page!");
        $this->assertContains($this->userData->getName(), $encounterPage->getUserName(),
            "User name on page doesn't match profile user name ");
    }

    public function testFailedSignInWithFakeCredits()
    {

        $FakeUser = new UserCredits(UserCredits::FAKE_USER);

        $this->singInPage->SingIn($FakeUser->getEmail(), $FakeUser->getPass());

        $this->assertContains('Неверный логин или пароль', $this->singInPage->getFormErrorMessage(),
            "Wrong failed authentication message!");
    }

    public function testRememberMeCheckBoxSaveEmailInformation()
    {

        $this->singInPage->SingIn($this->userData->getEmail(), $this->userData->getPass());
        $this->singInPage->SelectRememberMeCheckbox();

        $encounterPage = new EncounterPage($this);
        $this->wd->wait(5)->until(WebDriverExpectedCondition::titleIs($encounterPage::PAGE_TITLE));

        $encounterPage->logOutButtonClick();
        $encounterPage->acceptLogOutAllert();

        $basePage = new BasePage($this);
        $basePage->goToSingInPage();

        $this->assertContains($this->userData->getEmail(), $this->singInPage->getValueAttributeOfEmailInput(),
            "Email information isn't saved in input field");
    }

    /**
     * @depends testSuccessSignIn
     */
    public function testSaveSession()
    {
        $this->markTestIncomplete("This test is not ready yet!");
    }

}