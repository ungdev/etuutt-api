<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Covoit;
use App\Entity\CovoitAlert;
use App\Entity\CovoitMessage;
use App\Entity\User;
use App\Util\Text;
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

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        // Récupération des users
        $users = $manager->getRepository(User::class)->findAll();
        
        // Création de 30 covoits
        for ($i = 0; $i < 30; ++$i) {
            // Créations d'un covoit
            $covoit = new Covoit();
            
            // On ajoute un user en tant qu'auteur du covoit
            $covoit->setAuthor($faker->randomElement($users));
            
            // Création d'une description
            $covoit->setDescription(Text::createRandomText(5, 9));

            $covoit->setCapacity($faker->numberBetween(1, 4));
            
            $covoit->setPrice($faker->numberBetween(1000, 3000));
            
            // On a 75% de chance d'avoir un URL
            if ($faker->boolean(75)) {
                $covoit->setBlablacarUrl($faker->imageUrl());
            }
            
            // On remplit la liste d'utilisateurs si IsFull est vrai, sinon on en met un nombre aléatoire inférieur
            $subscribedUsers = [];
            $subscribedUsers[] = $covoit->getAuthor();
            for ($j = 0; $j < $faker->numberBetween(0, $covoit->getCapacity()); ++$j) {
                do {
                    $newUser = $faker->randomElement($users);
                } while (\in_array($newUser, $subscribedUsers, true));

                $subscribedUsers[] = $newUser;
                $covoit->addPassenger($newUser);
            }

            // Création des timestamps et des addresses
            $covoit->setCreatedAt($faker->dateTimeBetween('-3 years'));

            $days = (new DateTime())->diff($covoit->getCreatedAt())->days;
            $covoit->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));

            $covoit->setStartAddress($faker->streetAddress);
            $covoit->setEndAddress($faker->streetAddress);

            $days = (new DateTime())->diff($covoit->getUpdatedAt())->days;
            $covoit->setStartAt($faker->dateTimeBetween('-'.$days.' days'));

            $days = (new DateTime())->diff($covoit->getStartAt())->days;
            $covoit->setEndAt($faker->dateTimeBetween('-'.$days.' days'));

            // On persiste le covoit dans la base de données
            $manager->persist($covoit);
        }

        $manager->flush();

        // Création de 30 covoitAlerts
        for ($i = 0; $i < 30; ++$i) {
            // Créations d'une covoitAlert
            $covoitAlert = new CovoitAlert();

            // On lie l'alerte à un user
            $covoitAlert->setUser($faker->randomElement($users));

            $covoitAlert->setPriceMax($faker->numberBetween(10, 25));

            // Création des timestamps
            $covoitAlert->setCreatedAt($faker->dateTimeThisYear);
            $days = (new DateTime())->diff($covoitAlert->getCreatedAt())->days;
            $covoitAlert->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));

            $days = (new DateTime())->diff($covoitAlert->getUpdatedAt())->days;
            $covoitAlert->setStartAt($faker->dateTimeBetween('-'.$days.' days'));

            $days = (new DateTime())->diff($covoitAlert->getStartAt())->days;
            $covoitAlert->setEndAt($faker->dateTimeBetween('-'.$days.' days'));

            // Création des villes de départ et d'arrivée
            $covoitAlert->setStartCityId($faker->uuid);
            $covoitAlert->setEndCityid($faker->uuid);

            // On persiste l'alerte dans la base de données
            $manager->persist($covoitAlert);
        }

        $manager->flush();

        // Récupération des covoits
        $covoits = $manager->getRepository(Covoit::class)->findAll();

        // Création de 100 covoitMessages
        for ($i = 0; $i < 100; ++$i) {
            // Créations d'un covoitMessage
            $covoitMessage = new CovoitMessage();

            // On lie le message à un covoit
            $covoitMessage->setCovoit($faker->randomElement($covoits));

            // On ajoute un user en tant qu'auteur du covoitMessage
            $covoitMessage->setAuthor($faker->randomElement($users));

            // Création du texte
            $covoitMessage->setBody(Text::createRandomText(5, 9));

            // Création des timestamps
            $covoitMessage->setCreatedAt($faker->dateTimeBetween('-3 years'));
            $days = (new DateTime())->diff($covoitMessage->getCreatedAt())->days;
            $covoitMessage->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            // Soft delete aléatoire d'un Timestamps (Avec une chance de 10%)
            if ($faker->boolean(10)) {
                $days = (new DateTime())->diff($covoitMessage->getCreatedAt())->days;
                $covoitMessage->setDeletedAt($faker->dateTimeBetween('-'.$days.' days'));
            }

            // On persiste le covoitMessage dans la base de données
            $manager->persist($covoitMessage);
        }

        $manager->flush();
    }
}
