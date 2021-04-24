<?php

namespace App\DataFixtures;

use App\Entity\CovoitAlert;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CovoitAlertSeeder extends Fixture implements DependentFixtureInterface
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

        //Création de 30 covoitAlerts
        for ($i = 0; $i < 30; ++$i) {
            //Créations d'une covoitAlert
            $covoitAlert = new CovoitAlert();

            //On lie l'alerte à un user
            $covoitAlert->setUser($faker->randomElement($users));

            $covoitAlert->setPriceMax($faker->numberBetween(10, 25));

            //Création des timestamps
            $covoitAlert->setCreatedAt($faker->dateTimeThisYear);
            $days = (new DateTime())->diff($covoitAlert->getCreatedAt())->days;
            $covoitAlert->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));

            $days = (new DateTime())->diff($covoitAlert->getUpdatedAt())->days;
            $covoitAlert->setStartDate($faker->dateTimeBetween('-'.$days.' days'));

            $days = (new DateTime())->diff($covoitAlert->getStartDate())->days;
            $covoitAlert->setEndDate($faker->dateTimeBetween('-'.$days.' days'));

            //Création des villes de départ et d'arrivée
            $covoitAlert->setStartCity($faker->city);

            $covoitAlert->setEndCity($faker->city);

            //On persiste l'alerte dans la base de données
            $manager->persist($covoitAlert);
        }
        $manager->flush();
    }
}
