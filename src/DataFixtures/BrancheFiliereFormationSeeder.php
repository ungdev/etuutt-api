<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use App\Entity\Traduction;
use App\Entity\User;
use App\Entity\UserBranche;
use App\Entity\UserFormation;
use App\Entity\UTTBranche;
use App\Entity\UTTFiliere;
use App\Entity\UTTFormation;
use App\Entity\UTTFormationFollowingMethod;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BrancheFiliereFormationSeeder extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserSeeder::class,
            SemesterGenerator::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        //  Création du TC et de 8 branches
        for ($i = 0; $i < 9; ++$i) {
            //  Créations d'une entité
            if (0 === $i) {
                $code = 'TC';
            } else {
                $code = strtoupper($faker->randomLetter.$faker->randomLetter.$faker->randomLetter);
            }
            $branche = new UTTBranche($code);

            $branche->setName(implode(' ', $faker->words));

            //  Création d'une traduction
            $descriptionTraduction = new Traduction('UTTBranche:'.$branche->getCode());
            $branche->setDescriptionTraduction($descriptionTraduction);

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

            //  Création des autres informations pour les branches
            if (0 !== $i) {
                $branche->setExitSalary($faker->numberBetween(35000, 45000));
                $branche->setEmploymentRate($faker->numberBetween(7500, 10000) / 100);
                $branche->setCDIRate($faker->numberBetween(7500, 10000) / 100);
                $branche->setAbroadEmploymentRate($faker->numberBetween(7500, 10000) / 100);
            }

            //  On persiste l'entité dans la base de données
            $manager->persist($branche);
            $manager->flush();
        }

        //  Création de filières pour les branches, hors TC
        $brancheRepository = $manager->getRepository(UTTBranche::class);
        $branches = $brancheRepository->findAll();
        foreach ($branches as $branche) {
            if ('TC' !== $branche->getCode()) {
                //  Génération de 3 filières pour chaque branche
                for ($i = 0; $i < 3; ++$i) {
                    $filiere = new UTTFiliere(strtoupper($faker->randomLetter.$faker->randomLetter.$faker->randomLetter));
                    $filiere->setUTTBranche($branche);
                    $filiere->setName(implode(' ', $faker->words));

                    //  Création d'une traduction
                    $descriptionTraduction = new Traduction('UTTFiliere:'.$filiere->getCode());
                    $filiere->setDescriptionTraduction($descriptionTraduction);

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

                    $manager->persist($filiere);
                }
            }
        }
        $manager->flush();

        //  Attribution de branche et de filiere aux utilisateurs (UserBranche)

        //  Récupération des users et des branches
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();
        $filiereRepository = $manager->getRepository(UTTFiliere::class);
        $filieres = $filiereRepository->findAll();
        $semesterRepository = $manager->getRepository(Semester::class);
        $branches = $brancheRepository->findAll();

        foreach ($users as $user) {
            //  Création de la variable pivot
            $userUTTBranche = new UserBranche();
            $userUTTBranche->setUser($user);
            $branche = $faker->randomElement($branches);
            $userUTTBranche->setUTTBranche($branche);

            //  Ajout d'une filière pour la moitié des users
            if ($faker->boolean()) {
                $userUTTBranche->setUTTFiliere($faker->randomElement($branche->getUTTFilieres()->getValues()));
            }

            $createdAt = $user->getTimestamps()->getCreatedAt();
            $userUTTBranche->setCreatedAt($createdAt);
            $userUTTBranche->setSemester($semesterRepository->getSemesterOfDate($createdAt));
            $userUTTBranche->setSemesterNumber($faker->numberBetween(1, 6));

            $manager->persist($userUTTBranche);
        }
        $manager->flush();

        //  Création de formations
        for ($i = 0; $i < 3; ++$i) {
            switch ($i) {
                case 0:
                    $formation = new UTTFormation('Ingénieur');

                    break;

                case 1:
                    $formation = new UTTFormation('Doctorat');

                    break;

                case 2:
                    $formation = new UTTFormation('Master');

                    break;
            }

            //  Création d'une traduction
            $descriptionTraduction = new Traduction('UTTFormation:'.$formation->getName());
            $formation->setDescriptionTraduction($descriptionTraduction);

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

            $manager->persist($formation);
        }
        $manager->flush();

        //  Création de following methodes
        for ($i = 0; $i < 3; ++$i) {
            switch ($i) {
                case 0:
                    $followingMethod = new UTTFormationFollowingMethod('Présentiel');

                    break;

                case 1:
                    $followingMethod = new UTTFormationFollowingMethod('Distanciel');

                    break;

                case 2:
                    $followingMethod = new UTTFormationFollowingMethod('Alternance');

                    break;
            }

            //  Création d'une traduction
            $descriptionTraduction = new Traduction('FollowingMethod:'.$followingMethod->getName());
            $followingMethod->setDescriptionTraduction($descriptionTraduction);

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

            $manager->persist($followingMethod);
        }
        $manager->flush();

        //  Création de UserFormation

        //  Récupération des users, formations, et méthode de suivi
        $users = $userRepository->findAll();
        $formations = $manager->getRepository(UTTFormation::class)->findAll();
        $followingMethod = $manager->getRepository(UTTFormationFollowingMethod::class)->findAll();

        foreach ($users as $user) {
            $userUTTFormation = new UserFormation();
            $userUTTFormation->setUser($user);
            $userUTTFormation->setUTTFormation($faker->randomElement($formations));
            $userUTTFormation->setFollowingMethod($faker->randomElement($followingMethod));
            $days = (new DateTime())->diff($user->getTimestamps()->getCreatedAt())->days;
            $userUTTFormation->setCreatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));

            $manager->persist($userUTTFormation);
        }
        $manager->flush();
    }
}
