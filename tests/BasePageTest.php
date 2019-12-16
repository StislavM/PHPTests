<?php declare(strict_types=1);

namespace My;

use My\Pages\BasePage;
use My\Pages\EncounterPage;
use My\Steward\UserCredentials;

require_once __DIR__ . '/../src/UserCredentials.php';

/**
 * Class BasePageTest
 *
 * @package My
 * @group First
 */
class BasePageTest extends AbstractTestCase
{
    protected $basePage;
    protected $credits;
    protected $userData;

    /**
     * @before
     */
    public function init()
    {
        $this->basePage = new BasePage($this);
        $this->wd->get(self::$baseUrl);
        $this->userData = new UserCredentials();
    }

    public function testSuccessFacebookAuth()
    {

        $this->markTestIncomplete('It is needed to add facebook account data');

        //add real Facebook user information
        $email = $this->userData->getFacebookLogin();
        $pass = $this->userData->getFacebookPass();
        $userName = $this->userData->getName();

        $this->basePage->useFacebookAuthorization($email, $pass);

        $encounterPage = new EncounterPage($this);
        $encounterPage->isEncounterPageLoad();

        $this->assertContains('encounters', $this->wd->getCurrentURL(),
            "After Authorization we get not into encounters page!");
        $this->assertContains($userName, $encounterPage->getUserName(),
            "User name on encounter page in sidebar menu doesn't match profile user name");
    }

    public function testGoToSingInPage()
    {
        $this->basePage->goToSingInPage();
        $currentUrl = $this->wd->getCurrentURL();

        $this->assertContains('signin', $currentUrl,
            sprintf("Singin link leads to wrong url! It leads to %s", $currentUrl));
    }

    /**
     * @dataProvider LocalesProvider
     */
    public function testChangeLanguage($language, $local, $translation)
    {
        $this->basePage->changeLanguage($language);

        $this->assertContains($local, $this->wd->getCurrentURL(), "Locale in url doesn't match selected language");

        $titleText = $this->basePage->getSinginTitleText();
        $this->assertEquals($translation, $titleText, "Title message is not translated into selected language!");
    }

    public function LocalesProvider()
    {
        return [
            "Italiano language" => ['Italiano', 'it', 'persone ne fanno già parte, vieni anche tu!'],
            "Russian language" => ['Русский', 'ru', 'уже с нами. Присоединяйтесь!'],
            "Dansk language" => ['Dansk', 'da', 'personer er allerede tilmeldt - kom med!'],
        ];
    }

}
