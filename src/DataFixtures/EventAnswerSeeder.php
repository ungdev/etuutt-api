<?php

namespace App\DataFixtures;

use App\Entity\EventAnswer;
use App\Entity\Event;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EventAnswerSeeder extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            UserSeeder::class,
            EventSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        //Récupération des événements et des utilisateurs
        $eventRepository = $manager->getRepository(Event::class);
        $events = $eventRepository->findAll();
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();

        //Création de 100 event_answers
        for ($i=0; $i < 100; $i++) {
            //Création d'une event_answer
            $eventAnswer = new EventAnswer();

            $eventAnswer->setEvent($faker->randomElement($events));

            $eventAnswer->setUser($faker->randomElement($users));

            //Création de la réponse
            $possibleAnswers = ['yes', 'no', 'probably'];
            $eventAnswer->setAnswer($faker->randomElement($possibleAnswers));

            //Création du commentaire
            $comment = "";
            for ($j=0; $j < 3; $j++) {
                $comment .= "<p>";
                $comment .= str_repeat($faker->word, 9);
                $comment .= "</p>";
            }
            $eventAnswer->setComment($comment);

            //Création des timestamps
            $eventAnswer->setCreatedAt($faker->dateTimeBetween('-3 years'));
            $days = (new DateTime())->diff($eventAnswer->getCreatedAt())->days;
            $eventAnswer->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            //Soft delete aléatoire d'une Event_answer (Avec une chance de 10%)
            if ($faker->boolean(10)) {
                $days = (new DateTime())->diff($eventAnswer->getCreatedAt())->days;
                $eventAnswer->setDeletedAt($faker->dateTimeBetween('-'.$days.' days'));
            }

            //On persiste event_answer dans la base de données
            $manager->persist($eventAnswer);
        }
        $manager->flush();
    }
}
