<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\AssoMember;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RoleSeeder extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            AssoMemberSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");
        $roles = [];

        //Création de 100 rôles
        for ($i=0; $i < 100; $i++) {
            //Créations d'un rôle
            $role = new Role();

            $role->setName(str_shuffle($faker->word.$faker->word));

            array_push($roles, $role);
            //On persiste le rôle dans la base de données
            $manager->persist($role);
        }
        $manager->flush();

        //Attribution de rôles à des membres

        //Récupération des membres
        $assoMemberRepository = $manager->getRepository(AssoMember::class);
        $assoMembers = $assoMemberRepository->findAll();

        foreach ($assoMembers as $assoMember) {
            for ($i=0; $i < $faker->numberBetween(0, 5); $i++) {
                $assoMember->addRole($faker->randomElement($roles));
            }
        }
        $manager->flush();
    }
}
