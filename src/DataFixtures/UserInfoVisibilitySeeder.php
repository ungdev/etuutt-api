<?php

namespace App\DataFixtures;

use App\DataFixtures\UserSeeder;
use App\DataFixtures\GroupSeeder;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use App\Entity\Group;
use App\Entity\UserPreference;
use App\Entity\User;
use App\Entity\UserInfos;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use phpDocumentor\Reflection\Types\Mixed_;

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

        foreach ($users as $user) {
            
            //  On ajoute une entité UserPreference
            $preference = new UserPreference();
            $preference->setUser($user);
            $preference->setBirthdayDisplayOnlyAge($faker->boolean());
            $preference->setLanguage($faker->languageCode());
            $preference->setWantDaymail($faker->boolean());
            $preference->setBirthdayDisplayOnlyAge($faker->boolean());
            //  Ajout de visibilité pour l'emplois du temps
            $this->setFieldVisibility($preference, "addScheduleVisibility", $faker, $groupRepo);
            $manager->persist($preference);

            //  On ajoute une entité UserInfos
            $infos = new UserInfos();
            $infos->setUser($user);
            $infos->setSex($faker->randomElement(["Masculin", "Féminin", "Autre"]));
            $this->setFieldVisibility($infos, "addSexVisibility", $faker, $groupRepo);
            $infos->setNationality($faker->countryCode());
            $this->setFieldVisibility($infos, "addNationalityVisibility", $faker, $groupRepo);
            $infos->setBirthday($faker->dateTimeBetween("-25 years", "-20 years"));
            $this->setFieldVisibility($infos, "addBirthdayVisibility", $faker, $groupRepo);
            $infos->setAvatar($faker->imageUrl());
            $infos->setNickname($faker->word());
            $infos->setPassions($faker->word()." ".$faker->word()." ".$faker->word());
            $infos->setWebsite($faker->imageUrl());
            $manager->persist($infos);
            
            
        }
        
        $manager->flush();

    }


    /**
     * Cette fonction attribut des groupes de visibilité avec le setter passé en paramètre
     * @param object $entity L'entité dont on va appeler une méthode
     * @param String $methodName Le adder du champ dont on va modifier la visibilité
     * @param Generator $faker La factory faker pour générer des données
     * @param GroupRepository $groupRepo Le repository pour accéder aux Users
     */
    public static function setFieldVisibility(object $entity, String $methodName, Generator $faker, GroupRepository $groupRepo)
    {
        $groups = $groupRepo->findAll();
        $groupChoice = $faker->numberBetween(0, 2);
        switch ($groupChoice) {
            case 0:
                $group = $groupRepo->findOneBy(['name' => 'Privé']);
                $entity->caller($methodName, $group);
                break;
            case 1:
                $group = $groupRepo->findOneBy(['name' => 'Public']);
                $entity->caller($methodName, $group);
                break;
            case 2:
                for ($i=0; $i < $faker->numberBetween(1, 6); $i++) { 
                    $group = $faker->randomElement($groups);
                    $entity->caller($methodName, $group);
                }
                break;
        }
    }

    
}
