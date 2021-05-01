<?php

namespace App\DataFixtures;

use App\Entity\Asso;
use App\Entity\AssoKeyword;
use App\Entity\AssoMemberPermission;
use App\Entity\AssoMessage;
use App\Entity\Traduction;
use App\Util\Text;
use DateTime;
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

        //Création de 40 associations
        for ($i = 0; $i < 40; ++$i) {
            //Créations d'une Asso
            $asso = new Asso();

            $asso->setLogin(strtolower($faker->word.'_'.$faker->word.$faker->word));
            $asso->setName($faker->word.' '.$faker->word);

            //Création d'une traduction pour la petite description
            $descriptionShortTraduction = new Traduction('Asso:'.$asso->getName().':ShortDesc');
            $asso->setDescriptionShortTraduction($descriptionShortTraduction);
            $manager->persist($descriptionShortTraduction);

            $description = Text::createRandomText(1, 9);
            $descriptionShortTraduction->setFrench($description);
            $descriptionShortTraduction->setEnglish($description);
            $descriptionShortTraduction->setSpanish($description);
            $descriptionShortTraduction->setGerman($description);
            $descriptionShortTraduction->setChinese($description);

            //Création d'une traduction pour la description
            $descriptionTraduction = new Traduction('Asso:'.$asso->getName().':Desc');
            $asso->setDescriptionTraduction($descriptionTraduction);
            $manager->persist($descriptionTraduction);

            $description = Text::createRandomText(5, 9);
            $descriptionTraduction->setFrench($description);
            $descriptionTraduction->setEnglish($description);
            $descriptionTraduction->setSpanish($description);
            $descriptionTraduction->setGerman($description);
            $descriptionTraduction->setChinese($description);

            //Création des autres champs
            $asso->setMail($faker->email);
            $asso->setPhoneNumber($faker->phoneNumber);
            $asso->setWebsite($faker->imageUrl());
            $asso->setLogo($faker->imageUrl());

            //Création des timestamps
            $asso->setCreatedAt($faker->dateTimeBetween('-3 years'));
            $days = (new DateTime())->diff($asso->getCreatedAt())->days;
            $asso->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            //Soft delete aléatoire d'un Timestamps (Avec une chance de 10%)
            if ($faker->boolean(10)) {
                $days = (new DateTime())->diff($asso->getCreatedAt())->days;
                $asso->setDeletedAt($faker->dateTimeBetween('-'.$days.' days'));
            }

            //On persiste l'asso dans la base de données
            $manager->persist($asso);
        }
        $manager->flush();

        //Récupération des assos
        $assoRepository = $manager->getRepository(Asso::class);
        $assos = $assoRepository->findAll();

        for ($i = 0; $i < 100; ++$i) {
            $assoMessage = new AssoMessage();

            //Attribution de message à des assos
            $assoMessage->setAsso($faker->randomElement($assos));

            $assoMessage->setTitle(str_shuffle($faker->word.$faker->word));

            //Création d'une traduction
            $descriptionTraduction = new Traduction('AssoMessage:'.$assoMessage->getTitle());
            $assoMessage->setBodyTraduction($descriptionTraduction);
            $manager->persist($descriptionTraduction);

            $description = Text::createRandomText(5, 9);
            $descriptionTraduction->setFrench($description);
            $descriptionTraduction->setEnglish($description);
            $descriptionTraduction->setSpanish($description);
            $descriptionTraduction->setGerman($description);
            $descriptionTraduction->setChinese($description);

            $assoMessage->setDate($faker->dateTimeThisYear);

            $assoMessage->setSendToMobile($faker->boolean(30));
            $assoMessage->setSendAsDaymail($faker->boolean(80));

            $assoMessage->setCreatedAt($faker->dateTimeBetween('-3 years'));

            //On persiste message dans la base de données
            $manager->persist($assoMessage);
        }
        $manager->flush();

        //Création de 100 permissions
        for ($i = 0; $i < 100; ++$i) {
            //Créations d'une permission
            $permission = new AssoMemberPermission(str_shuffle($faker->word.$faker->word));

            //On persiste la permission dans la base de données
            $manager->persist($permission);
        }
        $manager->flush();

        //Création de 100 mots-clés
        $keywords = [];
        for ($i = 0; $i < 100; ++$i) {
            //Créations d'un mot-clé
            $keyword = new AssoKeyword(str_shuffle($faker->word.$faker->word));

            $keywords[] = $keyword;
            //On persiste le mot-clé dans la base de données
            $manager->persist($keyword);
        }
        $manager->flush();

        //Attribution de mots-clé à des assos

        //Récupération des assos
        $assoRepository = $manager->getRepository(Asso::class);
        $assos = $assoRepository->findAll();

        foreach ($assos as $asso) {
            for ($i = 0; $i < $faker->numberBetween(0, 10); ++$i) {
                $asso->addKeyword($faker->randomElement($keywords));
            }
        }
        $manager->flush();
    }
}
