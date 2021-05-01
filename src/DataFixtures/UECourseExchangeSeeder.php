<?php

namespace App\DataFixtures;

use App\Entity\UECourse;
use App\Entity\UECourseExchange;
use App\Entity\UECourseExchangeResponse;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UECourseExchangeSeeder extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserSeeder::class,
            UECourseSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();
        $courses = $manager->getRepository(UECourse::class)->findAll();

        //  Création de 100 demandes d'échange de cours
        for ($i = 0; $i < 100; ++$i) {
            $exchange = new UECourseExchange();
            $exchange->setAuthor($faker->randomElement($users));
            $exchange->setCourseFrom($faker->randomElement($courses));
            $exchange->setCourseTo($faker->randomElement($courses));
            $exchange->setStillAvailable($faker->boolean());
            $body = '';
            for ($j = 0; $j < 5; ++$j) {
                $body .= '<p>';
                for ($k = 0; $k < 9; ++$k) {
                    $body .= $faker->word();
                }
                $body .= '</p>';
            }
            $exchange->setBody($body);
            $exchange->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            $days = (new DateTime())->diff($exchange->getCreatedAt())->days;
            $exchange->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            //  Soft delete aléatoire d'un échange (Avec une chance de 2%)
            if ($faker->boolean(2)) {
                $days = (new DateTime())->diff($exchange->getUpdatedAt())->days;
                $exchange->setDeletedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            }
            $manager->persist($exchange);
        }
        $manager->flush();

        //  Création de 200 demandes réponses
        $exchanges = $manager->getRepository(UECourseExchange::class)->findAll();
        for ($i = 0; $i < 200; ++$i) {
            $response = new UECourseExchangeResponse();
            $response->setAuthor($faker->randomElement($users));
            $response->setExchange($faker->randomElement($exchanges));
            $body = '';
            for ($j = 0; $j < 5; ++$j) {
                $body .= '<p>';
                for ($k = 0; $k < 9; ++$k) {
                    $body .= $faker->word();
                }
                $body .= '</p>';
            }
            $response->setBody($body);
            $response->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            $days = (new DateTime())->diff($response->getCreatedAt())->days;
            $response->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            //  Soft delete aléatoire d'une réponse (Avec une chance de 2%)
            if ($faker->boolean(2)) {
                $days = (new DateTime())->diff($response->getUpdatedAt())->days;
                $response->setDeletedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            }
            $manager->persist($response);
        }
        $manager->flush();
    }
}
