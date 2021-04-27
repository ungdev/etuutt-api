<?php

namespace App\DataFixtures;

use App\Entity\UE;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UESeeder extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; ++$i) {
            //  Créations d'une UE
            $ue = new UE();
            $ue->setCode(strtoupper($faker->randomLetter.$faker->randomLetter.$faker->randomDigit.$faker->randomDigit));
            $name = "";
            for ($k = 0; $k < 9; ++$k) {
                $name .= ($faker->word() . " ");
            }
            $ue->setName($name);
            $ue->setValidationRate($faker->randomFloat(2, 50, 100));
            $ue->setStillAvailable($faker->boolean(80));
            $createdAt = $faker->dateTimeBetween('-3 years', 'now');
            $ue->setCreatedAt($createdAt);
            $days = (new DateTime())->diff($ue->getCreatedAt())->days;
            $ue->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            $manager->persist($ue);

        }
        $manager->flush();
    }
}
