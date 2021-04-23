<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use App\Entity\Traduction;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BadgeSeeder extends Fixture implements DependentFixtureInterface
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

        //  Création de 40 badges
        for ($i = 0; $i < 40; ++$i) {
            //  Créations d'un Badge
            $badge = new Badge();

            //  On crée une série de 5 badges
            if (0 <= $i && $i < 5) {
                if (0 === $i) {
                    $serie = $faker->word;
                }
                $badge->setSerie($serie);
                $badge->setLevel($i);
            }

            //  Et une autre de 6 badges
            if (5 <= $i && $i < 11) {
                if (5 === $i) {
                    $serie = $faker->word;
                }
                $badge->setSerie($serie);
                $badge->setLevel($i - 5);
            }

            $badge->setName($faker->word.$faker->word);
            $badge->setPicture($faker->imageUrl());

            //  Création d'une traduction
            $descriptionTraduction = new Traduction('Badge:'.$badge->getName().$badge->getLevel());
            $badge->setDescriptionTraduction($descriptionTraduction);
            $manager->persist($descriptionTraduction);

            $description = '';
            for ($j = 0; $j < 5; ++$j) {
                $description .= '<p>';
                for ($k = 0; $k < 9; ++$k) {
                    $description .= $faker->word();
                }
                $description .= '</p>';
            }
            $descriptionTraduction->setFrench($description);
            $descriptionTraduction->setEnglish($description);
            $descriptionTraduction->setSpanish($description);
            $descriptionTraduction->setGerman($description);
            $descriptionTraduction->setChinese($description);

            //  Création des timestamps
            $badge->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            //  Soft delete aléatoire d'un Timestamps (Avec une chance de 10%)
            if ($faker->boolean(10)) {
                $days = (new DateTime())->diff($badge->getCreatedAt())->days;
                $badge->setDeletedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            }

            //  On persiste le Badge dans la base de données
            $manager->persist($badge);
            $manager->flush();
        }

        //  Attribution de badges à des utilisateurs

        //  Récupération des users et des badges
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();
        $badgeRepository = $manager->getRepository(Badge::class);
        $badges = $badgeRepository->findAll();

        foreach ($users as $user) {
            for ($i = 0; $i < $faker->numberBetween(0, 10); ++$i) {
                $badge = $faker->randomElement($badges);
                $user->addBadge($badge);
            }
        }
        $manager->flush();
    }
}
