<?php

namespace App\DataFixtures;

use App\Entity\Asso;
use App\Entity\AssoKeyword;
use App\Entity\AssoMembership;
use App\Entity\AssoMembershipPermission;
use App\Entity\AssoMembershipRole;
use App\Entity\AssoMessage;
use App\Entity\User;
use App\Util\Text;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AssoSeeder extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Création de 40 associations
        for ($i = 0; $i < 40; ++$i) {
            // Créations d'une Asso
            $asso = new Asso();

            $asso->setLogin(strtolower($faker->word.'_'.$faker->word.$faker->word));
            $asso->setName($faker->word.' '.$faker->word.' '.$faker->word);

            //  Récupération de la description
            $descriptionShortTranslation = $asso->getDescriptionShortTranslation();

            $description = Text::createRandomText(1, 9);
            $descriptionShortTranslation->setFrench($description);
            $descriptionShortTranslation->setEnglish($description);
            $descriptionShortTranslation->setSpanish($description);
            $descriptionShortTranslation->setGerman($description);
            $descriptionShortTranslation->setChinese($description);

            // Création d'une traduction pour la description
            $descriptionTranslation = $asso->getDescriptionTranslation();

            $description = Text::createRandomText(5, 9);
            $descriptionTranslation->setFrench($description);
            $descriptionTranslation->setEnglish($description);
            $descriptionTranslation->setSpanish($description);
            $descriptionTranslation->setGerman($description);
            $descriptionTranslation->setChinese($description);

            // Création des autres champs
            $asso->setMail($faker->email);
            $asso->setPhoneNumber($faker->phoneNumber);
            $asso->setWebsite($faker->imageUrl());
            $asso->setLogo($faker->imageUrl());

            // Création des timestamps
            $asso->setCreatedAt($faker->dateTimeBetween('-3 years'));
            $days = (new \DateTime())->diff($asso->getCreatedAt())->days;
            $asso->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            // Soft delete aléatoire d'un Timestamps (Avec une chance de 10%)
            if ($faker->boolean(10)) {
                $days = (new \DateTime())->diff($asso->getCreatedAt())->days;
                $asso->setDeletedAt($faker->dateTimeBetween('-'.$days.' days'));
            }

            // On persiste l'asso dans la base de données
            $manager->persist($asso);
        }

        $manager->flush();

        // Récupération des assos
        $assos = $manager->getRepository(Asso::class)->findAll();

        for ($i = 0; $i < 100; ++$i) {
            $assoMessage = new AssoMessage();

            // Attribution de message à des assos
            $assoMessage->setAsso($faker->randomElement($assos));

            // Création d'une traduction pour le title
            $descriptionTranslation = $assoMessage->getTitleTranslation();
            $descriptionTranslation->setFrench(str_shuffle($faker->word.$faker->word.$faker->word));
            $descriptionTranslation->setEnglish(str_shuffle($faker->word.$faker->word.$faker->word));
            $descriptionTranslation->setSpanish(str_shuffle($faker->word.$faker->word.$faker->word));
            $descriptionTranslation->setGerman(str_shuffle($faker->word.$faker->word.$faker->word));
            $descriptionTranslation->setChinese(str_shuffle($faker->word.$faker->word.$faker->word));

            // Création d'une traduction pour le body
            $descriptionTranslation = $assoMessage->getBodyTranslation();
            $description = Text::createRandomText(5, 9);
            $descriptionTranslation->setFrench($description);
            $descriptionTranslation->setEnglish($description);
            $descriptionTranslation->setSpanish($description);
            $descriptionTranslation->setGerman($description);
            $descriptionTranslation->setChinese($description);

            $assoMessage->setDate($faker->dateTimeThisYear);

            $assoMessage->setSendToMobile($faker->boolean(30));
            $assoMessage->setSendAsDaymail($faker->boolean(80));

            $assoMessage->setCreatedAt($faker->dateTimeBetween('-3 years'));

            // On persiste message dans la base de données
            $manager->persist($assoMessage);
        }

        $manager->flush();

        // Création de 100 mots-clés
        $keywords = [];
        for ($i = 0; $i < 100; ++$i) {
            // Créations d'un mot-clé
            $keyword = new AssoKeyword(str_shuffle($faker->word.$faker->word));

            $keywords[] = $keyword;
            // On persiste le mot-clé dans la base de données
            $manager->persist($keyword);
        }

        $manager->flush();

        // Attribution de mots-clé à des assos

        foreach ($assos as $asso) {
            for ($i = 0; $i < $faker->numberBetween(0, 10); ++$i) {
                $asso->addKeyword($faker->randomElement($keywords));
            }
        }

        $manager->flush();

        // Récupération des utilisateurs
        $users = $manager->getRepository(User::class)->findAll();

        // On attribue un utilisateur à entre 0 et 3 assos
        foreach ($users as $user) {
            for ($i = 0; $i < $faker->numberBetween(0, 3); ++$i) {
                $assoMember = new AssoMembership();

                $assoMember->setUser($user);

                // Assignation du membre à une asso
                $assoMember->setAsso($faker->randomElement($assos));

                $assoMember->setStartAt($faker->dateTimeBetween('-3 years'));
                $days = (new \DateTime())->diff($asso->getCreatedAt())->days;
                $assoMember->setEndAt($faker->dateTimeBetween('-'.$days.' days'));

                $assoMember->setCreatedAt($faker->dateTimeBetween('-3 years'));

                // On persiste le membre dans la base de données
                $manager->persist($assoMember);
            }
        }

        $manager->flush();

        // Création de 100 permissions
        $permissions = [];
        for ($i = 0; $i < 100; ++$i) {
            // Créations d'une permission
            $permission = new AssoMembershipPermission(str_shuffle($faker->word.$faker->word));

            $permissions[] = $permission;
            // On persiste la permission dans la base de données
            $manager->persist($permission);
        }

        $manager->flush();

        // Attribution de permissions à des membres

        // Récupération des membres et des permissions
        $assoMembers = $manager->getRepository(AssoMembership::class)->findAll();

        foreach ($assoMembers as $assoMember) {
            for ($i = 0; $i < $faker->numberBetween(0, 5); ++$i) {
                $assoMember->addPermission($faker->randomElement($permissions));
            }
        }

        $manager->flush();

        // Création de 100 rôles
        $roles = [];

        // Liste des rôles possibles
        $possibleRoles = ['president', 'vice_president', 'treasurer', 'vice_treasurer', 'secretary', 'vice_secretary', 'manager'];

        foreach ($possibleRoles as $currentRole) {
            // Créations d'un rôle
            $role = new AssoMembershipRole($currentRole);

            // Création d'une traduction
            $descriptionTranslation = $role->getDescriptionTranslation();

            $description = Text::createRandomText(1, 9);
            $descriptionTranslation->setFrench($description);
            $descriptionTranslation->setEnglish($description);
            $descriptionTranslation->setSpanish($description);
            $descriptionTranslation->setGerman($description);
            $descriptionTranslation->setChinese($description);

            $roles[] = $role;
            // On persiste le rôle dans la base de données
            $manager->persist($role);
        }

        $manager->flush();

        // Attribution de rôles à des membres

        foreach ($assoMembers as $assoMember) {
            if ($faker->boolean(10)) {
                $assoMember->addRole($faker->randomElement($roles));
            }
        }

        $manager->flush();
    }
}
