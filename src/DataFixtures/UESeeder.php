<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use App\Entity\UE;
use App\Entity\UECredit;
use App\Entity\UECreditCategory;
use App\Entity\User;
use App\Entity\UserUESubscription;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UESeeder extends Fixture implements DependentFixtureInterface
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

        //  Création de 6 catégories d'UEs
        for ($i = 0; $i < 6; ++$i) {
            $category = new UECreditCategory($faker->randomLetter.$faker->randomLetter.$faker->randomLetter);
            $name = '';
            for ($k = 0; $k < 9; ++$k) {
                $name .= ($faker->word().' ');
            }
            $category->setName($name);
            $manager->persist($category);
        }
        $manager->flush();

        //  Création de 100 UEs
        for ($i = 0; $i < 100; ++$i) {
            //  Créations d'une UE
            $ue = new UE();
            $ue->setCode(strtoupper($faker->randomLetter.$faker->randomLetter.$faker->randomDigit.$faker->randomDigit));
            $name = '';
            for ($k = 0; $k < 9; ++$k) {
                $name .= ($faker->word().' ');
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

        //  Ajout de 6 UEs pour tous les utilisateurs
        $users = $manager->getRepository(User::class)->findAll();
        $ues = $manager->getRepository(UE::class)->findAll();
        $semesterRepo = $manager->getRepository(Semester::class);
        foreach ($users as $user) {
            //  Pour chaque utilisateur, on ajoute 6 UEs
            for ($i = 0; $i < 6; ++$i) {
                $subscription = new UserUESubscription();
                $subscription->setUser($user);
                $subscription->setUE($faker->randomElement($ues));
                $subscription->setCreatedAt(new DateTime());
                $subscription->setSemester($semesterRepo->getSemesterOfDate($subscription->getCreatedAt()));
                $manager->persist($subscription);
            }
        }
        $manager->flush();

        //  Attribution de crédits aux UEs
        $categories = $manager->getRepository(UECreditCategory::class)->findAll();
        foreach ($ues as $ue) {
            for ($i = 0; $i < $faker->numberBetween(1, 2); ++$i) {
                $credits = new UECredit();
                $credits->setUE($ue);
                $credits->setCategory($faker->randomElement($categories));
                $credits->setCredits($faker->numberBetween(2, 6));
                $manager->persist($credits);
            }
        }
        $manager->flush();
    }
}
