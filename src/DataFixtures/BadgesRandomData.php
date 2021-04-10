<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use App\Entity\Traduction;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BadgesRandomData extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");
        $userRepository = $manager->getRepository(User::class);

        for ($i=0; $i < 30; $i++) {

            //  Créations d'un Badge
            $badge = new Badge();

            //  On crée une série de badge
            if (0 <= $i && $i <=4) {
                if ($i == 0) {
                    $serie = $faker->word;
                }
                $badge->setSerie($serie);
                $badge->setLevel($i);
            }

            $badge->setName($faker->word.$faker->word);
            $badge->setPicture($faker->imageUrl());

            //  Création d'une traduction
            $descriptionTraduction = new Traduction("Badge:".$badge->getName().$badge->getLevel());
            $badge->setDescriptionTraduction($descriptionTraduction);
            $manager->persist($descriptionTraduction);

            $description = "";
            for ($j=0; $j < 5; $j++) { 
                $description .= "<p>";
                for ($k=0; $k < 9; $k++) { 
                    $description .= $faker->word();
                }
                $description .= "</p>";
            }
            $descriptionTraduction->setFrench($description);
            $descriptionTraduction->setEnglish($description);
            $descriptionTraduction->setSpanish($description);
            $descriptionTraduction->setGerman($description);
            $descriptionTraduction->setChinese($description);

            //  Création des timestamps
            $badge->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            //  Soft delete aléatoire d'un Timestamps (Avec une chance de 20%)
            if ($faker->boolean(10)) {
                $days = (new DateTime())->diff($badge->getCreatedAt())->days;
                $badge->setDeletedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            }

            //  On persiste le Badge dans la base de données
            $manager->persist($badge);
            $manager->flush();

        }
    }
}
