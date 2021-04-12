<?php

namespace App\DataFixtures;

use App\DataFixtures\UserSeeder;
use App\Repository\UserRepository;
use App\DataFixtures\SemesterGenerator;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\Traduction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTime;
use Faker\Factory;

class GroupSeeder extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            UserSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");


        //  Création de 20 groupes
        for ($i=0; $i < 20; $i++) {

            //  Créations d'une entité
            switch ($i) {
                case 0:
                    $name = "Privé";
                    break;
                case 1:
                    $name = "Public";
                    break;
                default:
                    $name = implode(" ",$faker->words);
                    break;
            }
            $group = new Group($name);

            //  Création d'une traduction
            $descriptionTraduction = new Traduction("Group:".$group->getName());
            $group->setDescriptionTraduction($descriptionTraduction);

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

            //  Création des timesstamps
            $group->setCreatedAt($faker->dateTimeBetween("-5 years"));
            $days = (new DateTime())->diff($group->getCreatedAt())->days;
            $group->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            //  Soft delete aléatoire d'un User (Avec une chance de 1%)
            if ($faker->boolean(10)) {
                $days = (new DateTime())->diff($group->getUpdatedAt())->days;
                $group->setDeletedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            }
            
            
            //  On persiste l'entité dans la base de données
            $manager->persist($group);
            $manager->flush();
        }


        //  Attribution de groupes aux utilisateurs

        //  Récupération des users et des groups
        $users = $manager->getRepository(User::class)->findAll();
        $groups = $manager->getRepository(Group::class)->findAll();

        foreach ($groups as $group) {

            if ($group->getName() != "Privé") {
                
                if ($group->getName() == "Public") {
                    $inGroupRate = 100;
                } else {
                    $inGroupRate = $faker->numberBetween(0, 30);
                }
                foreach ($users as $user) {
                    if ($faker->boolean($inGroupRate)) {
                        $group->addUser($user);
                    }
                }
            }

        }
        $manager->flush();

    }
}
