<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use App\Entity\UE;
use App\Entity\UECredit;
use App\Entity\UECreditCategory;
use App\Entity\UEInfo;
use App\Entity\UEStarCriterion;
use App\Entity\UEStarVote;
use App\Entity\UEWorkTime;
use App\Entity\User;
use App\Entity\UserUESubscription;
use App\Repository\SemesterRepository;
use App\Util\Text;
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

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //  Récupération de $semesterRepository
        /** @var SemesterRepository $semesterRepository */
        $semesterRepository = $manager->getRepository(Semester::class);

        //  Création de 6 catégories d'UEs
        for ($i = 0; $i < 6; ++$i) {
            $category = new UECreditCategory($faker->randomLetter.$faker->randomLetter.$faker->randomLetter);
            $name = Text::createRandomLine(9);
            $category->setName($name);
            $manager->persist($category);
        }

        $manager->flush();

        //  Création de 100 UEs
        for ($i = 0; $i < 100; ++$i) {
            //  Créations d'une UE
            $ue = new UE();
            $ue->setCode(strtoupper($faker->randomLetter.$faker->randomLetter.$faker->randomDigit.$faker->randomDigit));
            $name = Text::createRandomLine(9);
            $ue->setName($name);
            $ue->setValidationRate($faker->randomFloat(2, 50, 100));
            $createdAt = $faker->dateTimeBetween('-3 years');
            $ue->setCreatedAt($createdAt);
            $days = (new \DateTime())->diff($ue->getCreatedAt())->days;
            $ue->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            $ue->addOpenSemester($semesterRepository->getSemesterOfDate($ue->getCreatedAt()));
            $ue->addOpenSemester($semesterRepository->getSemesterOfDate($ue->getUpdatedAt()));
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
                $workTime->setInternship($faker->numberBetween(0, 28));
            }

            $manager->persist($workTime);

            $info = new UEInfo();
            $info->setUE($ue);
            $text = Text::createRandomText(5, 9);
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
                $subscription->setCreatedAt(new \DateTime());
                $subscription->setSemester($semesterRepository->getSemesterOfDate($subscription->getCreatedAt()));
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
            $name = Text::createRandomLine(7);
            $criterion->setName($name);
            //  Création d'une traduction
            $descriptionTranslation = $criterion->getDescriptionTranslation();

            $description = Text::createRandomText(5, 9);
            $descriptionTranslation->setFrench($description);
            $descriptionTranslation->setEnglish($description);
            $descriptionTranslation->setSpanish($description);
            $descriptionTranslation->setGerman($description);
            $descriptionTranslation->setChinese($description);

            $manager->persist($descriptionTranslation);
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
                $vote->setCreatedAt($faker->dateTimeBetween('-3 years'));
                $manager->persist($vote);
            }
        }

        $manager->flush();
    }
}
