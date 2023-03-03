<?php

namespace App\DataFixtures;

use App\Entity\UECourse;
use App\Entity\UECourseExchange;
use App\Entity\UECourseExchangeReply;
use App\Entity\User;
use App\Util\Text;
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

    public function load(ObjectManager $manager): void
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
            $body = Text::createRandomText(5, 9);
            $exchange->setBody($body);
            $exchange->setCreatedAt($faker->dateTimeBetween('-3 years'));
            $days = (new \DateTime())->diff($exchange->getCreatedAt())->days;
            $exchange->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            //  Soft delete aléatoire d'un échange (Avec une chance de 2%)
            if ($faker->boolean(2)) {
                $days = (new \DateTime())->diff($exchange->getUpdatedAt())->days;
                $exchange->setDeletedAt($faker->dateTimeBetween('-'.$days.' days'));
            }

            $manager->persist($exchange);
        }

        $manager->flush();

        //  Création de 200 demandes réponses
        $exchanges = $manager->getRepository(UECourseExchange::class)->findAll();
        for ($i = 0; $i < 200; ++$i) {
            $response = new UECourseExchangeReply();
            $response->setAuthor($faker->randomElement($users));
            $response->setExchange($faker->randomElement($exchanges));
            $body = Text::createRandomText(5, 9);
            $response->setBody($body);
            $response->setCreatedAt($faker->dateTimeBetween('-3 years'));
            $days = (new \DateTime())->diff($response->getCreatedAt())->days;
            $response->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            //  Soft delete aléatoire d'une réponse (Avec une chance de 2%)
            if ($faker->boolean(2)) {
                $days = (new \DateTime())->diff($response->getUpdatedAt())->days;
                $response->setDeletedAt($faker->dateTimeBetween('-'.$days.' days'));
            }

            $manager->persist($response);
        }

        $manager->flush();
    }
}
