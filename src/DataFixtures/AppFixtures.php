<?php

namespace App\DataFixtures;

use App\DataFixtures\Providers\DataProviders;
use App\Entity\Card;
use App\Entity\Language;
use App\Entity\News;
use App\Entity\Page;
use App\Entity\PageContent;
use App\Entity\Picture;
use App\Entity\PopUp;
use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $connection;
    private $passwordHasher;

    public function __construct(Connection $connection, UserPasswordHasherInterface $passwordHasher)
    {
        $this->connection = $connection;
        $this->passwordHasher = $passwordHasher;
    }

    public function truncate()
    {
        // Desactivation of the foreign key constraints
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        $this->connection->executeQuery('TRUNCATE TABLE card');
        $this->connection->executeQuery('TRUNCATE TABLE language');
        $this->connection->executeQuery('TRUNCATE TABLE news');
        $this->connection->executeQuery('TRUNCATE TABLE page');
        $this->connection->executeQuery('TRUNCATE TABLE page_content');
        $this->connection->executeQuery('TRUNCATE TABLE picture');
        $this->connection->executeQuery('TRUNCATE TABLE pop_up');
        $this->connection->executeQuery('TRUNCATE TABLE user');
    }
    public function load(ObjectManager $manager): void
    {
        $this->truncate();
        $faker = Factory::create('fr_FR');
        $data = new DataProviders;

        // Create users
        $userArray = [];
        foreach($data->getUsers() as $user){
            $newUser = (new User)
                ->setEmail($user['email'])
                ->setFirstname($user['firstname'])
                ->setLastname($user['lastname'])
                ->setRoles([$user['role']])
                ->setPhone($user['phone']);

            $newUser->setPassword($this->passwordHasher->hashPassword(
                $newUser,
                $user['password']
            ));

            $manager->persist($newUser);

            $userArray[] = $newUser;
        }

        $today = new DateTimeImmutable();

        // Create Cards
        $cardArray = [];
        for($i = 0; $i < 30; $i++){
            // If the card is used
            $isUsed = rand(0,1);
            $boughtAt = new DateTimeImmutable($faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:m:s'));
            $deadline = $boughtAt->modify('+1 year');

            $newCard = (new Card)
                ->setAmount($faker->randomNumber(3, false))
                ->setBoughtAt($boughtAt)
                ->setGifter($faker->firstName())
                ->setReceiver($faker->firstName())
                ->setReference($faker->unique()->randomNumber(6, false))
                ->setUser($userArray[array_rand($userArray)]);

            $newCard->setLimitedDate($deadline);

            // If the deadline is not exceeded and the card is used
            if($deadline > $today && $isUsed === 1){
                // Generate a random date between $boughtAt and now
                $randomDate = $faker->dateTimeBetween($boughtAt->format('Y-m-d H:m:s'), 'now')->format('Y-m-d H:m:s');
                $usedAt = new DateTimeImmutable($randomDate);
                $newCard->setUsedAt($usedAt);
            }

            $manager->persist($newCard);

            $cardArray[] = $newCard;
        }

        // Create pages
        $pageArray = [];
        foreach($data->getPages() as $namePage){
            $newPage = (new Page)
                ->setName($namePage);

            $manager->persist($newPage);

            $pageArray[] = $newPage;
        }

        // Create languages
        $languageArray = [];
        foreach($data->getLanguages() as $country){
            $newLanguage = (new Language)
                ->setCountry($country);

            $manager->persist($newLanguage);

            $languageArray[] = $newLanguage;
        }
        // Create news
        $newsArray = [];
        $sliderPosition = 1;
        for($i = 1; $i < 5; $i++){
            $newNews = (new News)
                ->setTitle($faker->sentence())
                ->setContent($faker->paragraph())
                ->setIsHomeEvent(rand(0,1))
                ->setPublishedAt(new DateTimeImmutable($faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:m:s')));

            if($newNews->isIsHomeEvent()){
                $newNews->setSliderPosition($sliderPosition);
                $sliderPosition++;
            }   
            $manager->persist($newNews);

            $newsArray[] = $newNews;
        }

        // Create contents of pages
        $contentArray = [];
        foreach($pageArray as $page) {

            for($i = 1; $i < 4; $i++){
                $newContent = (new PageContent)
                    ->setTitle($faker->sentence())
                    ->setPageOrder($i)
                    ->setContent($faker->paragraph())
                    ->setPage($page)
                    ->setLanguage($languageArray[0]);
    
                $manager->persist($newContent);

                $contentArray[] = $newContent;
            }
        }

        // Create pictures
        for($i = 0; $i < 10; $i++){
            $isNews = rand(0,3);
            $newPicture = (new Picture)
                ->setPath($faker->imageUrl(640, 480, 'food', true))
                ->setAlt('food');

            if($isNews !== 1){
                $newPicture->addPageContent($contentArray[array_rand($contentArray)]);
            } else {
                $newPicture->setNews($newsArray[array_rand($newsArray)]);
            }

            $manager->persist($newPicture);
        }

        // Create pop-up
        $newPopUP = (new PopUp)
            ->setTitle($faker->sentence())
            ->setContent($faker->paragraph())
            ->setIsActive(rand(0,1));

        $manager->persist($newPopUP);


        $manager->flush();
    }
}
