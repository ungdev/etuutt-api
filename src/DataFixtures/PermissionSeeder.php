<?php

namespace App\DataFixtures;

use App\Entity\AssoMember;
use App\Entity\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PermissionSeeder extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            AssoMemberSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $permissions = [];

        //Création de 100 permissions
        for ($i = 0; $i < 100; ++$i) {
            //Créations d'une permission
            $permission = new Permission();

            $permission->setName(str_shuffle($faker->word.$faker->word));

            $permissions[] = $permission;
            //On persiste la permission dans la base de données
            $manager->persist($permission);
        }
        $manager->flush();

        //Attribution de permissions à des membres

        //Récupération des membres
        $assoMemberRepository = $manager->getRepository(AssoMember::class);
        $assoMembers = $assoMemberRepository->findAll();

        foreach ($assoMembers as $assoMember) {
            for ($i = 0; $i < $faker->numberBetween(0, 5); ++$i) {
                $assoMember->addPermission($faker->randomElement($permissions));
            }
        }
        $manager->flush();
    }
}
