<?php
/**
 * Created by PhpStorm.
 * User: stislavm
 * Date: 11/12/2019
 * Time: 11:58
 */

namespace My\Steward;

use Faker;

//class for working with users credits
class UserCredits
{
    const FAKE_USER = "FakeUser";
    const REAL_USER = "RealUser";

    const USER_NAME = "UserName";
    const USER_PASS = "Pass";
    const USER_EMAIL = "Email";

    private $credits;

    function __construct($userType = self::REAL_USER, $id = null)
    {

        if ($userType == self::FAKE_USER) {
            $this->credits = $this->CreateFakeUserCredits();

            return;
        }
        if ($userType == self::REAL_USER) {
            if ($id) {
                // Here should be function that find User Data by ID in DB
            } else {
                //this is stub for test with my own credits
                $this->credits = $this->MyOwnCreditsStub();
            }
        } else {
            throw new \RuntimeException(sprintf('Unknown UserType "%s"', $userType));
        }
    }

    private function MyOwnCreditsStub(): array
    {
        $data = [
            //todo add credits
            self::USER_EMAIL => 'testmail_id11@mail.ru',
            self::USER_PASS => 'qwe123QWE',
            self::USER_NAME => 'TestUserName',
        ];

        return $data;
    }

    private function CreateFakeUserCredits(): array
    {
        $faker = Faker\Factory::create();
        $data = [
            self::USER_EMAIL => $faker->email,
            self::USER_PASS => $faker->password,
            self::USER_NAME => $faker->name,
        ];

        return $data;
    }

    public function getUserCredits(): array
    {
        return $this->credits;
    }

    public function getEmail(): string
    {
        return $this->credits[self::USER_EMAIL];
    }

    public function getPass(): string
    {
        return $this->credits[self::USER_PASS];
    }

    public function getName(): string
    {
        return $this->credits[self::USER_NAME];
    }
}