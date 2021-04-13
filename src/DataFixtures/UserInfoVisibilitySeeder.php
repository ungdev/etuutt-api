<?php

namespace App\DataFixtures;

use App\DataFixtures\UserSeeder;
use App\DataFixtures\GroupSeeder;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use App\Entity\Group;
use App\Entity\UserPreference;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserInfoVisibilitySeeder extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            UserSeeder::class,
            GroupSeeder::class
        ];
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        $users = $manager->getRepository(User::class)->findAll();
        $groupRepo = $manager->getRepository(Group::class);
        $groups = $groupRepo->findAll();

        foreach ($users as $user) {
            
            //  On ajoute une entité UserPreference
            $preference = new UserPreference();
            $preference->setUser($user);
            $preference->setBirthdayDisplayOnlyAge($faker->boolean());
            $preference->setLanguage($faker->languageCode());
            $preference->setWantDaymail($faker->boolean());
            $preference->setBirthdayDisplayOnlyAge($faker->boolean());
            
            //  Ajout de visibilité pour l'emplois du temps
            $groupChoice = $faker->numberBetween(0, 2);
            switch ($groupChoice) {
                case 0:
                    $group = $groupRepo->findOneBy(['name' => 'Privé']);
                    $preference->addScheduleVisibility($group);
                    break;
                case 1:
                    $group = $groupRepo->findOneBy(['name' => 'Public']);
                    $preference->addScheduleVisibility($group);
                    break;
                case 2:
                    for ($i=0; $i < $faker->numberBetween(1, 6); $i++) { 
                        $group = $faker->randomElement($groups);
                        $preference->addScheduleVisibility($group);
                    }
                    break;
            }
            $manager->persist($preference);
        }
        
        $manager->flush();

    }
}
