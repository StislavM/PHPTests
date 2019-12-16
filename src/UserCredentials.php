<?php declare(strict_types=1);

namespace My\Steward;

use PDO;
use Faker;
use PDOException;

//add configuration constant file
require_once 'Config.php';

//class for working with users credits
class UserCredentials
{
    private $credentials;
    private $pdo;

    // testmail_id11@mail.ru - is special user for tests.
    function __construct($userEmail = 'testmail_id11@mail.ru')
    {
        try {
            $sql = 'SELECT b.UserEmail,b.UserPass,f.FbLogin,f.FbPass,d.UserId,d.UserName,d.Gender,d.Age 
                FROM BadooUsers b
                      INNER JOIN FacebookCredential f ON b.UserEmail = f.UserEmail
                      INNER JOIN UserData d ON b.UserID  = d.UserID
                WHERE b.UserEmail = :email
                ORDER BY b.UserEmail ';

            //connect to database
            $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);

            $data = $this->pdo->prepare($sql);
            $data->bindParam(':email', $userEmail, 536870912, 30);
            $data->execute();
            $result = $data->fetch();

        } catch (PDOException $exception) {
            throw new \RuntimeException($exception->getMessage(), (int)$exception->getCode());
        }

        if (!$result) {
            throw new \RuntimeException(sprintf('It is no users with "%s" email in database', $userEmail));
        }

        $this->credentials = [
            USER_EMAIL => $result[USER_EMAIL],
            USER_PASS => $result[USER_PASS],
            USER_NAME => $result[USER_NAME],
            ID => $result[ID],
            FB_LOGIN => $result[FB_LOGIN],
            FB_PASS => $result[FB_PASS],
            AGE => $result[AGE],
            GENDER => $result[GENDER],
        ];
    }

    public function getBadooCredentials(): array
    {
        return [
            USER_EMAIL => $this->credentials[USER_EMAIL],
            USER_PASS => $this->credentials[USER_PASS],
        ];
    }

    public function getFacebookCredentials(): array
    {
        return [
            FB_LOGIN => $this->credentials[FB_LOGIN],
            FB_PASS => $this->credentials[FB_PASS],
        ];
    }

    public function generateFakeUserCredentials()
    {
        $faker = Faker\Factory::create();
        $this->credentials=[
            USER_EMAIL => $faker->email,
            USER_PASS => $faker->password,
            USER_NAME => $faker->name,
            ID => $faker->numberBetween(0,10),
            FB_LOGIN => $faker->email,
            FB_PASS => $faker->password,
            AGE =>  $faker->numberBetween(10,50),
            GENDER => 'male',
        ];
    }

    public function getEmail(): string
    {
        return $this->credentials[USER_EMAIL];
    }

    public function getBadooPass(): string
    {
        return $this->credentials[USER_PASS];
    }

    public function getFacebookPass(): string
    {
        return $this->credentials[FB_PASS];
    }

    public function getFacebookLogin(): string
    {
        return $this->credentials[FB_LOGIN];
    }

    public function getName(): string
    {
        return $this->credentials[USER_NAME];
    }

    //todo  Script to create tables in database
}