<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Asso;
use App\Entity\Traduction;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EventSeeder extends Fixture implements DependentFixtureInterface
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

        //Récupération des assos
        $assoRepository = $manager->getRepository(Asso::class);
        $assos = $assoRepository->findAll();

        //Création de 30 events
        for ($i=0; $i < 30; $i++) {
            //Création d'un event
            $event = new Event();

            for ($j=0; $j < $faker->numberBetween(1, 3); $j++) {
                $event->addAsso($faker->randomElement($assos));
            }

            $event->setTitle(str_shuffle($faker->word.$faker->word.$faker->word));

            //Création des dates de début et de fin de event
            $event->setBegin($faker->dateTimeThisYear);
            $days = (new DateTime())->diff($event->getBegin())->days;
            $event->setEnd($faker->dateTimeBetween('-'.$days.' days'));

            $event->setIsAllDay($faker->boolean(75));

            if ($faker->boolean(90)) {
                $event->setLocation($faker->address);
            }

            //Création d'une traduction
            $descriptionTraduction = new Traduction("Event:".$event->getTitle());
            $event->setDescriptionTraduction($descriptionTraduction);
            $manager->persist($descriptionTraduction);

            $description = "";
            for ($j=0; $j < 3; $j++) {
                $description .= "<p>";
                $description .= str_repeat($faker->word, 9);
                $description .= "</p>";
            }
            $descriptionTraduction->setFrench($description);
            $descriptionTraduction->setEnglish($description);
            $descriptionTraduction->setSpanish($description);
            $descriptionTraduction->setGerman($description);
            $descriptionTraduction->setChinese($description);

            //Création des timestamps
            $event->setCreatedAt($faker->dateTimeBetween('-3 years'));
            $days = (new DateTime())->diff($event->getCreatedAt())->days;
            $event->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            //Soft delete aléatoire d'un Event (Avec une chance de 10%)
            if ($faker->boolean(10)) {
                $days = (new DateTime())->diff($event->getCreatedAt())->days;
                $event->setDeletedAt($faker->dateTimeBetween('-'.$days.' days'));
            }

            //On persiste event dans la base de données
            $manager->persist($event);
        }
        $manager->flush();
    }
}
