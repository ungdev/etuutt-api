<?php

namespace App\DataFixtures;

use App\Entity\Asso;
use App\Entity\Event;
use App\Entity\EventAnswer;
use App\Entity\EventCategory;
use App\Entity\Traduction;
use App\Entity\User;
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
            UserSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        //Récupération des assos
        $assoRepository = $manager->getRepository(Asso::class);
        $assos = $assoRepository->findAll();

        //Création de 30 events
        for ($i = 0; $i < 30; ++$i) {
            //Création d'un event
            $event = new Event();

            for ($j = 0; $j < $faker->numberBetween(1, 3); ++$j) {
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
            $descriptionTraduction = new Traduction('Event:'.$event->getTitle());
            $event->setDescriptionTraduction($descriptionTraduction);
            $manager->persist($descriptionTraduction);

            $description = $this->createRandomText(3, 9);
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

        //Récupération des événements et des utilisateurs
        $eventRepository = $manager->getRepository(Event::class);
        $events = $eventRepository->findAll();
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();

        //Création de 100 event_answers
        for ($i = 0; $i < 100; ++$i) {
            //Création d'une event_answer
            $eventAnswer = new EventAnswer();

            $eventAnswer->setEvent($faker->randomElement($events));

            $eventAnswer->setUser($faker->randomElement($users));

            //Création de la réponse
            $possibleAnswers = ['yes', 'no', 'probably'];
            $eventAnswer->setAnswer($faker->randomElement($possibleAnswers));

            //Création du commentaire
            $comment = $this->createRandomText(3, 9);
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

        //Création de 50 catégories
        $categories = [];
        for ($i = 0; $i < 50; ++$i) {
            //Créations d'une catégorie
            $name = '';
            for ($j = 0; $j < $faker->numberBetween(5, 20); ++$j) {
                $name .= $faker->randomLetter;
            }
            $category = new EventCategory($name);

            $categories[] = $category;
            //On persiste la catégorie dans la base de données
            $manager->persist($category);
        }
        $manager->flush();

        //Attribution de catégories à des events

        //Récupération des events
        $eventRepository = $manager->getRepository(Event::class);
        $events = $eventRepository->findAll();

        foreach ($events as $event) {
            for ($i = 0; $i < $faker->numberBetween(1, 3); ++$i) {
                $event->addCategory($faker->randomElement($categories));
            }
        }
        $manager->flush();
    }

    protected function createRandomText($nbOfParagraphs, $nbOfWordsPerParagraphs): string
    {
        $faker = Factory::create('fr_FR');

        $text = '';
        for ($j = 0; $j < $nbOfParagraphs; ++$j) {
            $text .= '<p>';
            $text .= str_repeat($faker->word, $nbOfWordsPerParagraphs);
            $text .= '</p>';
        }

        return $text;
    }
}
