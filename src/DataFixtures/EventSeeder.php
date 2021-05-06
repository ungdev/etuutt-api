<?php

namespace App\DataFixtures;

use App\Entity\Asso;
use App\Entity\AssoMembershipRole;
use App\Entity\Event;
use App\Entity\EventAnswer;
use App\Entity\EventCategory;
use App\Entity\EventPrivacy;
use App\Entity\Translation;
use App\Entity\User;
use App\Util\Text;
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
        $assos = $manager->getRepository(Asso::class)->findAll();

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
            $descriptionTranslation = new Translation('Event:'.$event->getTitle());
            $event->setDescriptionTranslation($descriptionTranslation);
            $manager->persist($descriptionTranslation);

            $description = Text::createRandomText(5, 9);
            $descriptionTranslation->setFrench($description);
            $descriptionTranslation->setEnglish($description);
            $descriptionTranslation->setSpanish($description);
            $descriptionTranslation->setGerman($description);
            $descriptionTranslation->setChinese($description);

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

        //Récupération des événements, des utilisateurs et des rôles
        $events = $manager->getRepository(Event::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        $assoMembershipRoles = $manager->getRepository(AssoMembershipRole::class)->findAll();

        //Création des event_privacy
        foreach ($events as $event) {
            //Création d'un event_privacy
            $eventPrivacy = new EventPrivacy();

            $eventPrivacy->setEvent($event);

            //On a 70% de chance que l'évènement soit public
            if ($faker->boolean(30)) {
                //On a 50% de chance d'ajouter une asso à la liste de permissions
                foreach ($assos as $asso) {
                    if ($faker->boolean()) {
                        $eventPrivacy->addAllowedAsso($asso);
                    }
                }
                //On a 25% de chance que tous les membres des associations soit autorisés
                if ($faker->boolean(75)) {
                    //On a 75% de chance d'ajouter un rôle à la liste de permissions
                    foreach ($assoMembershipRoles as $role) {
                        if ($faker->boolean(75)) {
                            $eventPrivacy->addAllowedRole($role);
                        }
                    }
                }
            }

            //On persiste event_answer dans la base de données
            $manager->persist($eventPrivacy);
        }
        $manager->flush();

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
            $comment = Text::createRandomText(5, 9);
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
        $events = $manager->getRepository(Event::class)->findAll();

        foreach ($events as $event) {
            for ($i = 0; $i < $faker->numberBetween(1, 3); ++$i) {
                $event->addCategory($faker->randomElement($categories));
            }
        }
        $manager->flush();
    }
}
