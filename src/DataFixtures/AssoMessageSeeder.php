<?php

namespace App\DataFixtures;

use App\Entity\AssoMessage;
use App\Entity\Asso;
use App\Entity\Traduction;
use App\Repository\UserRepository;
use App\Repository\AssoMessageRepository;
use App\DataFixtures\UserSeeder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AssoMessageSeeder extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            AssoSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        for ($i=0; $i < 100; $i++) {
            $assoMessage = new AssoMessage();

            //Récupération des assos
            $assoRepository = $manager->getRepository(Asso::class);
            $assos = $assoRepository->findAll();

            //Attribution de message à des assos
            $assoMessage->setAsso($faker->randomElement($assos));

            $assoMessage->setTitle($faker->word.$faker->word);

            //Création d'une traduction
            $descriptionTraduction = new Traduction("AssoMessage:".$assoMessage->getTitle());
            $assoMessage->setBodyTraduction($descriptionTraduction);
            $manager->persist($descriptionTraduction);

            $description = "";
            for ($j=0; $j < 5; $j++) {
                $description .= "<p>";
                $description .= str_repeat($faker->word, 9);
                $description .= "</p>";
            }
            $descriptionTraduction->setFrench($description);
            $descriptionTraduction->setEnglish($description);
            $descriptionTraduction->setSpanish($description);
            $descriptionTraduction->setGerman($description);
            $descriptionTraduction->setChinese($description);

            $assoMessage->setDate($faker->dateTimeThisYear);

            $assoMessage->setSendToMobile($faker->boolean(30));
            $assoMessage->setSendAsDaymail($faker->boolean(80));

            $assoMessage->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));

            //On persiste le Badge dans la base de données
            $manager->persist($assoMessage);
            $manager->flush();
        }
    }
}
