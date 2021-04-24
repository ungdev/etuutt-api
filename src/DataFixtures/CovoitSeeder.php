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
        $faker = Factory::create('fr_FR');

        //Récupération des users
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();

        //Création de 30 covoits
        for ($i = 0; $i < 30; ++$i) {
            //Créations d'un covoit
            $covoit = new Covoit();

            //On ajoute un user en tant qu'auteur du covoit
            $covoit->setAuthor($faker->randomElement($users));

            //Création d'une description
            $description = '';
            for ($j = 0; $j < 5; ++$j) {
                $description .= '<p>';
                $description .= str_repeat($faker->word, 9);
                $description .= '</p>';
            }
            $covoit->setDescription($description);

            $covoit->setCapacity($faker->numberBetween(1, 4));

            $covoit->setPrice($faker->numberBetween(10, 30));

            //On a 75% de chance d'avoir un URL
            if ($faker->boolean(75)) {
                $covoit->setBlablacarUrl($faker->imageUrl());
            }

            //On remplit la liste d'utilisateurs si IsFull est vrai, sinon on en met un nombre aléatoire inférieur
            $subscribedUsers = [];
            $subscribedUsers[] = $covoit->getAuthor();
            for ($j = 0; $j < $faker->numberBetween(0, $covoit->getCapacity()); ++$j) {
                do {
                    $newUser = $faker->randomElement($users);
                } while (\in_array($newUser, $subscribedUsers, true));
                $subscribedUsers[] = $newUser;
                $covoit->addUser($newUser);
            }

            //Création des timestamps et des addresses
            $covoit->setCreatedAt($faker->dateTimeBetween('-3 years'));

            $days = (new DateTime())->diff($covoit->getCreatedAt())->days;
            $covoit->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));

            $covoit->setStartAdress($faker->streetAddress);

            $days = (new DateTime())->diff($covoit->getUpdatedAt())->days;
            $covoit->setStartDate($faker->dateTimeBetween('-'.$days.' days'));

            $covoit->setEndAdress($faker->streetAddress);

            $days = (new DateTime())->diff($covoit->getStartDate())->days;
            $covoit->setEndDate($faker->dateTimeBetween('-'.$days.' days'));

            //On persiste le covoit dans la base de données
            $manager->persist($covoit);
        }
        $manager->flush();
    }
}
