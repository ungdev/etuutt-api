<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use App\Entity\Traduction;
use App\Entity\UE;
use App\Entity\UECredit;
use App\Entity\UECreditCategory;
use App\Entity\UEInfo;
use App\Entity\UEStarCriterion;
use App\Entity\UEStarVote;
use App\Entity\UEWorkTime;
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

        //  Récupération de $semesterRepo
        $semesterRepo = $manager->getRepository(Semester::class);

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
            $createdAt = $faker->dateTimeBetween('-3 years', 'now');
            $ue->setCreatedAt($createdAt);
            $days = (new DateTime())->diff($ue->getCreatedAt())->days;
            $ue->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            $ue->addOpenSemester($semesterRepo->getSemesterOfDate($ue->getCreatedAt()));
            $ue->addOpenSemester($semesterRepo->getSemesterOfDate($ue->getUpdatedAt()));
            $manager->persist($ue);

            $workTime = new UEWorkTime();
            $workTime->setUE($ue);
            if ($faker->boolean(95)) {
                $workTime->setCm($faker->numberBetween(0, 90));
                $workTime->setTd($faker->numberBetween(0, 90));
                $workTime->setThe($faker->numberBetween(0, 90));
                $workTime->setTp($faker->numberBetween(0, 90));
                $workTime->setProjet($faker->numberBetween(0, 90));
            } else {
                $workTime->setStage($faker->numberBetween(0, 28));
            }
            $manager->persist($workTime);

            $info = new UEInfo();
            $info->setUE($ue);
            $text = '';
            for ($j = 0; $j < 5; ++$j) {
                $text .= '<p>';
                for ($k = 0; $k < 9; ++$k) {
                    $text .= $faker->word();
                }
                $text .= '</p>';
            }
            $info->setAntecedent($text);
            $info->setComment($text);
            $info->setDegree($text);
            $info->setLanguages($text);
            $info->setMinors($text);
            $info->setObjectives($text);
            $info->setProgramme($text);
            $manager->persist($info);
        }
        $manager->flush();

        //  Ajout de 6 UEs pour tous les utilisateurs
        $users = $manager->getRepository(User::class)->findAll();
        $ues = $manager->getRepository(UE::class)->findAll();
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

        //  Création de critères de notations pour les UEs
        for ($i = 0; $i < 6; ++$i) {
            $criterion = new UEStarCriterion();
            $name = '';
            for ($k = 0; $k < 9; ++$k) {
                $name .= ($faker->word().' ');
            }
            $criterion->setName($name);
            //  Création d'une traduction
            $descriptionTraduction = new Traduction('UE_Star_Criterion:'.$criterion->getName());
            $criterion->setDescriptionTraduction($descriptionTraduction);

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

            $manager->persist($descriptionTraduction);
            $manager->persist($criterion);
        }
        $manager->flush();

        //  Attribution de stars pour les UEs
        $criterions = $manager->getRepository(UEStarCriterion::class)->findAll();
        foreach ($ues as $ue) {
            for ($i = 0; $i < $faker->numberBetween(0, 90); ++$i) {
                $vote = new UEStarVote();
                $vote->setUE($ue);
                $vote->setCriterion($faker->randomElement($criterions));
                $vote->setUser($faker->randomElement($users));
                $vote->setValue($faker->numberBetween(1, 5));
                $vote->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
                $manager->persist($vote);
            }
        }
        $manager->flush();
    }
}
