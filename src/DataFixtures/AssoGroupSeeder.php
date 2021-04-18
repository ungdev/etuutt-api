<?php

namespace App\DataFixtures;

use App\Entity\AssoGroup;
use App\Entity\Asso;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AssoGroupSeeder extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            AssoSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");

        //Récupération des assos
        $assoRepository = $manager->getRepository(Asso::class);
        $assos = $assoRepository->findAll();

        //Liste de groupes à créer par associations
        $toCreate = ["members", "presidents", "vice-presidents"];

        foreach ($assos as $asso) {
            foreach ($toCreate as $name) {
                $assoGroup = new AssoGroup();

                //Attribution du groupe a une asso
                $assoGroup->setAsso($asso);

                $assoGroup->setName($name);

                //Création du slug (assoName_groupName)
                $assoGroup->setSlug($asso->getName().'_'.$name);

                //Attribution d'un ordre pour l'affichage (plus petit affiché en 1er)
                $assoGroup->setPosition($faker->numberBetween(0, 100));

                $assoGroup->setIsVisible($faker->boolean(90));

                $assoGroup->setCreatedAt($faker->dateTimeBetween('-3 years'));

                //On persiste le groupe dans la base de données
                $manager->persist($assoGroup);
            }
        }
        $manager->flush();
    }
}
