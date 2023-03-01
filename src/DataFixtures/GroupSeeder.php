<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\User;
use App\Util\Slug;
use App\Util\Text;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GroupSeeder extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserSeeder::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();

        //  Création de 20 groupes
        for ($i = 0; $i < 20; ++$i) {
            //  Créations d'un group
            $group = new Group();

            $name = match ($i) {
                0 => 'Public',
                default => implode(' ', $faker->words),
            };
            $group->setName($name);
            $group->setSlug(Slug::slugify($name));
            $group->setIsVisible($faker->boolean(75));

            //  Création d'une traduction
            $descriptionTranslation = $group->getDescriptionTranslation();

            $description = Text::createRandomText(5, 9);
            $descriptionTranslation->setFrench($description);
            $descriptionTranslation->setEnglish($description);
            $descriptionTranslation->setSpanish($description);
            $descriptionTranslation->setGerman($description);
            $descriptionTranslation->setChinese($description);

            $manager->persist($descriptionTranslation);

            //  Ajout d'avatar
            $group->setAvatar($faker->imageUrl());

            //  Création des timestamps
            $group->setCreatedAt($faker->dateTimeBetween('-5 years'));
            $days = (new \DateTime())->diff($group->getCreatedAt())->days;
            $group->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            //  Soft delete aléatoire d'un User (Avec une chance de 1%)
            if ($faker->boolean(10)) {
                $days = (new \DateTime())->diff($group->getUpdatedAt())->days;
                $group->setDeletedAt($faker->dateTimeBetween('-'.$days.' days'));
            }

            //  On persiste l'entité dans la base de données
            $manager->persist($group);
        }

        $manager->flush();

        //  Attribution de groupes aux utilisateurs
        //  Récupération des groups
        $groups = $manager->getRepository(Group::class)->findAll();

        foreach ($groups as $group) {
            if ('Privé' !== $group->getName()) {
                foreach ($users as $user) {
                    if ($faker->boolean(15)) {
                        $group->addMember($user);
                    }

                    if ($faker->boolean(2)) {
                        $group->addAdmin($user);
                    }
                }
            }
        }

        $manager->flush();
    }
}
