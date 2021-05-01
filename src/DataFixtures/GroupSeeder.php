<?php

namespace App\DataFixtures;

use App\Entity\Asso;
use App\Entity\AssoMember;
use App\Entity\AssoMemberPermission;
use App\Entity\AssoMemberRole;
use App\Entity\Group;
use App\Entity\Traduction;
use App\Entity\User;
use App\Util\Slug;
use DateTime;
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
            AssoSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();
        $assos = $manager->getRepository(Asso::class)->findAll();

        //  Création de 20 groupes
        for ($i = 0; $i < 20; ++$i) {
            //  Créations d'un group
            $group = new Group();

            switch ($i) {
                case 0:
                    $name = 'Privé';

                    break;

                case 1:
                    $name = 'Public';

                    break;

                default:
                    $name = implode(' ', $faker->words);

                    break;
            }
            $group->setName($name);
            $group->setSlug(Slug::slugify($name));
            $group->setIsVisible($faker->boolean(75));

            //  Création d'une traduction
            $descriptionTraduction = new Traduction('Group:'.$group->getName());
            $group->setDescriptionTraduction($descriptionTraduction);

            $description = '';
            for ($j = 0; $j < 5; ++$j) {
                $description .= '<p>';
                for ($k = 0; $k < 9; ++$k) {
                    $description .= $faker->word();
                }
                $description .= '</p>';
            }
            $descriptionTraduction->setFrench($description);
            $descriptionTraduction->setEnglish($description);
            $descriptionTraduction->setSpanish($description);
            $descriptionTraduction->setGerman($description);
            $descriptionTraduction->setChinese($description);

            $manager->persist($descriptionTraduction);

            //  Création des timesstamps
            $group->setCreatedAt($faker->dateTimeBetween('-5 years'));
            $days = (new DateTime())->diff($group->getCreatedAt())->days;
            $group->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            //  Soft delete aléatoire d'un User (Avec une chance de 1%)
            if ($faker->boolean(10)) {
                $days = (new DateTime())->diff($group->getUpdatedAt())->days;
                $group->setDeletedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
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
                if ('Public' === $group->getName()) {
                    $inGroupRate = 100;
                } else {
                    $inGroupRate = $faker->numberBetween(0, 30);
                }
                foreach ($users as $user) {
                    if ($faker->boolean($inGroupRate)) {
                        $group->addMember($user);
                    }
                }
            }
        }
        $manager->flush();

        //  Création de groupes pour les assos
        //Liste de groupes à créer par associations
        $toCreate = ['Membres', 'Bureau'];
        foreach ($assos as $asso) {
            foreach ($toCreate as $name) {
                $group = new Group();

                //Attribution du groupe a une asso
                $group->setAsso($asso);
                $group->setName($asso->getName().' : '.$name);
                $group->setSlug(Slug::slugify($group->getName()));
                $group->setIsVisible($faker->boolean(90));

                //  Création d'une traduction
                $descriptionTraduction = new Traduction('Group:'.$group->getName());
                $group->setDescriptionTraduction($descriptionTraduction);

                $description = '';
                for ($j = 0; $j < 5; ++$j) {
                    $description .= '<p>';
                    for ($k = 0; $k < 9; ++$k) {
                        $description .= $faker->word();
                    }
                    $description .= '</p>';
                }
                $descriptionTraduction->setFrench($description);
                $descriptionTraduction->setEnglish($description);
                $descriptionTraduction->setSpanish($description);
                $descriptionTraduction->setGerman($description);
                $descriptionTraduction->setChinese($description);

                $manager->persist($descriptionTraduction);

                //  Création des timesstamps
                $group->setCreatedAt($faker->dateTimeBetween('-5 years'));
                $days = (new DateTime())->diff($group->getCreatedAt())->days;
                $group->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
                //  Soft delete aléatoire d'un User (Avec une chance de 1%)
                if ($faker->boolean(10)) {
                    $days = (new DateTime())->diff($group->getUpdatedAt())->days;
                    $group->setDeletedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
                }

                //On persiste le groupe dans la base de données
                $manager->persist($group);
            }
        }
        $manager->flush();

        //Récupération des utilisateurs et des groupes
        $users = $manager->getRepository(User::class)->findAll();
        $groups = $manager->getRepository(Group::class)->findAll();

        //On attribue un utilisateur à entre 0 et 3 groupes
        foreach ($users as $user) {
            $currentGroupAssos = [];
            for ($i = 0; $i < $faker->numberBetween(0, 3); ++$i) {
                $assoMember = new AssoMember();

                $assoMember->setUser($user);

                //Assignation du membre à un groupe (un membre ne peut pas faire partie de plusieurs groupes d'une même asso)
                do {
                    $group = $faker->randomElement($groups);
                } while (\in_array($group->getAsso(), $currentGroupAssos, true));
                $currentGroupAssos[] = $group->getAsso();
                $assoMember->setGroupName($group);

                $assoMember->setCreatedAt($faker->dateTimeBetween('-3 years'));

                //On persiste le membre dans la base de données
                $manager->persist($assoMember);
            }
        }
        $manager->flush();

        //Attribution de permissions à des membres

        //Récupération des membres et des permissions
        $assoMembers = $manager->getRepository(AssoMember::class)->findAll();
        $permissions = $manager->getRepository(AssoMemberPermission::class)->findAll();

        foreach ($assoMembers as $assoMember) {
            for ($i = 0; $i < $faker->numberBetween(0, 5); ++$i) {
                $assoMember->addPermission($faker->randomElement($permissions));
            }
        }
        $manager->flush();

        //Création de 100 rôles
        $roles = [];
        for ($i = 0; $i < 100; ++$i) {
            //Créations d'un rôle
            $role = new AssoMemberRole(str_shuffle($faker->word.$faker->word));

            $roles[] = $role;
            //On persiste le rôle dans la base de données
            $manager->persist($role);
        }
        $manager->flush();

        //Attribution de rôles à des membres

        //Récupération des membres
        $assoMemberRepository = $manager->getRepository(AssoMember::class);
        $assoMembers = $assoMemberRepository->findAll();

        foreach ($assoMembers as $assoMember) {
            for ($i = 0; $i < $faker->numberBetween(0, 5); ++$i) {
                $assoMember->addRole($faker->randomElement($roles));
            }
        }
        $manager->flush();
    }
}
