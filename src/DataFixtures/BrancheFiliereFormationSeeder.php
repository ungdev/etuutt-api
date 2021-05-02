<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use App\Entity\Translation;
use App\Entity\UE;
use App\Entity\User;
use App\Entity\UserBranche;
use App\Entity\UserFormation;
use App\Entity\UTTBranche;
use App\Entity\UTTFiliere;
use App\Entity\UTTFormation;
use App\Entity\UTTFormationFollowingMethod;
use App\Util\Text;
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
            UESeeder::class,
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
            $descriptionTranslation = new Translation('UTTBranche:'.$branche->getCode());
            $branche->setDescriptionTranslation($descriptionTranslation);

            $description = Text::createRandomText(5, 9);
            $descriptionTranslation->setFrench($description);
            $descriptionTranslation->setEnglish($description);
            $descriptionTranslation->setSpanish($description);
            $descriptionTranslation->setGerman($description);
            $descriptionTranslation->setChinese($description);

            $manager->persist($descriptionTranslation);

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
        $ues = $manager->getRepository(UE::class)->findAll();
        foreach ($branches as $branche) {
            if ('TC' !== $branche->getCode()) {
                //  Génération de 3 filières pour chaque branche
                for ($i = 0; $i < 3; ++$i) {
                    $filiere = new UTTFiliere(strtoupper($faker->randomLetter.$faker->randomLetter.$faker->randomLetter));
                    $filiere->setUTTBranche($branche);
                    $filiere->setName(implode(' ', $faker->words));

                    //  Attribution de 3 UEs pour chaque filiere
                    for ($j = 0; $j < 3; ++$j) {
                        $filiere->addUE($faker->randomElement($ues));
                    }

                    //  Création d'une traduction
                    $descriptionTranslation = new Translation('UTTFiliere:'.$filiere->getCode());
                    $filiere->setDescriptionTranslation($descriptionTranslation);

                    $description = Text::createRandomText(5, 9);
                    $descriptionTranslation->setFrench($description);
                    $descriptionTranslation->setEnglish($description);
                    $descriptionTranslation->setSpanish($description);
                    $descriptionTranslation->setGerman($description);
                    $descriptionTranslation->setChinese($description);

                    $manager->persist($descriptionTranslation);

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
            $formation = match ($i) {
                0 => new UTTFormation('Ingénieur'),
                1 => new UTTFormation('Doctorat'),
                2 => new UTTFormation('Master'),
            };

            //  Création d'une traduction
            $descriptionTranslation = new Translation('UTTFormation:'.$formation->getName());
            $formation->setDescriptionTranslation($descriptionTranslation);

            $description = Text::createRandomText(5, 9);
            $descriptionTranslation->setFrench($description);
            $descriptionTranslation->setEnglish($description);
            $descriptionTranslation->setSpanish($description);
            $descriptionTranslation->setGerman($description);
            $descriptionTranslation->setChinese($description);

            $manager->persist($descriptionTranslation);

            $manager->persist($formation);
        }
        $manager->flush();

        //  Création de following methodes
        for ($i = 0; $i < 3; ++$i) {
            $followingMethod = match ($i) {
                0 => new UTTFormationFollowingMethod('Présentiel'),
                1 => new UTTFormationFollowingMethod('Distanciel'),
                2 => new UTTFormationFollowingMethod('Alternance'),
            };

            //  Création d'une traduction
            $descriptionTranslation = new Translation('FollowingMethod:'.$followingMethod->getName());
            $followingMethod->setDescriptionTranslation($descriptionTranslation);

            $description = Text::createRandomText(5, 9);
            $descriptionTranslation->setFrench($description);
            $descriptionTranslation->setEnglish($description);
            $descriptionTranslation->setSpanish($description);
            $descriptionTranslation->setGerman($description);
            $descriptionTranslation->setChinese($description);

            $manager->persist($descriptionTranslation);

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
            $userUTTFormation->setCreatedAt($faker->dateTimeBetween('-'.$days.' days'));

            $manager->persist($userUTTFormation);
        }
        $manager->flush();
    }
}
