<?php

namespace App\DataFixtures;

use App\Entity\Covoit;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CovoitSeeder extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            UserSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        //  Récupération des users et des badges
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();

        //Création de 30 covoits
        for ($i=0; $i < 30; $i++) {
            //Créations d'un covoit
            $covoit = new Covoit();

            $covoit->setUser($faker->randomElement($users));

            $description = "";
            for ($j=0; $j < 5; $j++) {
                $description .= "<p>";
                $description .= str_repeat($faker->word, 9);
                $description .= "</p>";
            }
            $covoit->setDescription($description);

            $covoit->setCapacity($faker->numberBetween(1, 4));

            $covoit->setIsFull($faker->boolean(10));

            $covoit->setPrice($faker->numberBetween(10, 30));

            if ($faker->boolean(75)) {
                $covoit->setBlablacarUrl($faker->imageUrl());
            }

            $covoit->setCreatedAt($faker->dateTimeBetween('-3 years'));

            $days = (new DateTime())->diff($covoit->getCreatedAt())->days;
            $covoit->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));

            $covoit->setStartAdress($faker->streetAddress);

            $days = (new DateTime())->diff($covoit->getUpdatedAt())->days;
            $covoit->setStartDate($faker->dateTimeBetween('-'.$days.' days'));

            $covoit->setEndAdress($faker->streetAddress);

            $days = (new DateTime())->diff($covoit->getStartDate())->days;
            $covoit->setEndDate($faker->dateTimeBetween('-'.$days.' days'));

            $manager->persist($covoit);
        }
        $manager->flush();
    }
}
