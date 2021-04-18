<?php

namespace App\DataFixtures;

use App\Entity\AssoMember;
use App\Entity\AssoGroup;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AssoMemberSeeder extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            AssoGroupSeeder::class,
            UserSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        //Récupération des utilisateurs et des groupes
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();
        $assoGroupRepository = $manager->getRepository(AssoGroup::class);
        $assoGroups = $assoGroupRepository->findAll();

        //On attribue un utilisateur à entre 0 et 3 groupes
        foreach ($users as $user) {
            $currentGroupAssos = [];
            for ($i=0; $i < $faker->numberBetween(0, 3); $i++) {
                $assoMember = new AssoMember();

                $assoMember->setUser($user);

                //Assignation du membre à un groupe (un membre ne peut pas faire partie de plusieurs groupes d'une même asso)
                do {
                    $group = $faker->randomElement($assoGroups);
                } while (in_array($group->getAsso(), $currentGroupAssos));
                array_push($currentGroupAssos, $group->getAsso());
                $assoMember->setGroupName($group);

                $assoMember->setCreatedAt($faker->dateTimeBetween('-3 years'));

                //On persiste le membre dans la base de données
                $manager->persist($assoMember);
            }
        }
        $manager->flush();
    }
}
