<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Traduction;
use App\Entity\Branche;
use App\Entity\Semester;
use App\DataFixtures\UserSeeder;
use App\Entity\Filiere;
use App\Entity\UserBranche;
use App\Repository\UserRepository;
use App\Repository\BrancheRepository;
use App\Repository\FiliereRepository;
use App\Repository\SemesterRepository;
use App\DataFixtures\SemesterGenerator;
use App\Entity\Formation;
use App\Entity\FormationFollowingMethod;
use App\Entity\UserFormation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTime;
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

        $faker = Factory::create("fr_FR");


        //  Création du TC et de 8 branches
        for ($i=0; $i < 9; $i++) {

            //  Créations d'une entité
            if ($i == 0) {
                $code = "TC";
            } else {
                $code = strtoupper($faker->randomLetter.$faker->randomLetter.$faker->randomLetter);
            }
            $branche = new Branche($code);

            $branche->setName(implode(" ",$faker->words));

            //  Création d'une traduction
            $descriptionTraduction = new Traduction("Branche:".$branche->getCode());
            $branche->setDescriptionTraduction($descriptionTraduction);

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

            $manager->persist($descriptionTraduction);
            
            
            //  Création des autres informations pour les branches
            if ($i != 0) {
                $branche->setExitSalary($faker->numberBetween(35000, 45000));
                $branche->setEmploymentRate($faker->numberBetween(7500, 10000)/100);
                $branche->setCDIRate($faker->numberBetween(7500, 10000)/100);
                $branche->setAbroadEmploymentRate($faker->numberBetween(7500, 10000)/100);
            }
            
            //  On persiste l'entité dans la base de données
            $manager->persist($branche);
            $manager->flush();
        }


        //  Création de filières pour les branches, hors TC
        $brancheRepository = $manager->getRepository(Branche::class);
        $branches = $brancheRepository->findAll();
        foreach ($branches as $branche) {
            if ($branche->getCode() != "TC") {

                //  Génération de 3 filières pour chaque branche
                for ($i=0; $i < 3; $i++) { 
                    $filiere = new Filiere(strtoupper($faker->randomLetter.$faker->randomLetter.$faker->randomLetter));
                    $filiere->setBranche($branche);
                    $filiere->setName(implode(" ", $faker->words));

                    //  Création d'une traduction
                    $descriptionTraduction = new Traduction("Filiere:".$filiere->getCode());
                    $filiere->setDescriptionTraduction($descriptionTraduction);

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
        $filiereRepository = $manager->getRepository(Filiere::class);
        $filieres = $filiereRepository->findAll();
        $semesterRepository = $manager->getRepository(Semester::class);
        $branches = $brancheRepository->findAll();

        foreach ($users as $user) {

            //  Création de la variable pivot
            $userBranche = new UserBranche();
            $userBranche->setUser($user);
            $branche = $faker->randomElement($branches);
            $userBranche->setBranche($branche);

            //  Ajout d'une filière pour la moitié des users
            if ($faker->boolean()) {
                $userBranche->setFiliere($faker->randomElement($branche->getFilieres()->getValues()));
            }

            $createdAt = $user->getTimestamps()->getCreatedAt();
            $userBranche->setCreatedAt($createdAt);
            $userBranche->setSemester($semesterRepository->getSemesterOfDate($createdAt));
            $userBranche->setSemesterNumber($faker->numberBetween(1,6));

            $manager->persist($userBranche);
        }
        $manager->flush();


        //  Création de formations
        for ($i=0; $i < 3; $i++) {
            switch ($i) {
                case 0:
                    $formation = new Formation("Ingénieur");
                    break;
                case 1:
                    $formation = new Formation("Doctorat");
                    break;
                case 2:
                    $formation = new Formation("Master");
                    break;
            }

            //  Création d'une traduction
            $descriptionTraduction = new Traduction("Formation:".$formation->getName());
            $formation->setDescriptionTraduction($descriptionTraduction);

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

            $manager->persist($descriptionTraduction);

            $manager->persist($formation);

        }
        $manager->flush();


        //  Création de following methodes
        for ($i=0; $i < 3; $i++) {
            switch ($i) {
                case 0:
                    $followingMethod = new FormationFollowingMethod("Présentiel");
                    break;
                case 1:
                    $followingMethod = new FormationFollowingMethod("Distanciel");
                    break;
                case 2:
                    $followingMethod = new FormationFollowingMethod("Alternance");
                    break;
            }

            //  Création d'une traduction
            $descriptionTraduction = new Traduction("FollowingMethod:".$followingMethod->getName());
            $followingMethod->setDescriptionTraduction($descriptionTraduction);

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

            $manager->persist($descriptionTraduction);

            $manager->persist($followingMethod);

        }
        $manager->flush();


        //  Création de UserFormation

        //  Récupération des users, formations, et méthode de suivi
        $users = $userRepository->findAll();
        $formations = $manager->getRepository(Formation::class)->findAll();
        $followingMethod = $manager->getRepository(FormationFollowingMethod::class)->findAll();
        
        foreach ($users as $user) {
            $userFormation = new UserFormation();
            $userFormation->setUser($user);
            $userFormation->setFormation($faker->randomElement($formations));
            $userFormation->setFollowingMethod($faker->randomElement($followingMethod));
            $days = (new DateTime())->diff($user->getTimestamps()->getCreatedAt())->days;
            $userFormation->setCreatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));

            $manager->persist($userFormation);
        }
        $manager->flush();


    }
}
