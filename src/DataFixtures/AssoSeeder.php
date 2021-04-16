<?php

namespace App\DataFixtures;

use App\Entity\Asso;
use App\Entity\Traduction;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AssoSeeder extends Fixture
{

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        //Création de 40 associations
        for ($i=0; $i < 40; $i++) {
            //Créations d'une Asso
            $asso = new Asso();

            $asso->setLogin(strtolower($faker->word."_".$faker->word.$faker->word));
            $asso->setName($faker->word." ".$faker->word);

            //Création d'une traduction pour la petite description
            $descriptionShortTraduction = new Traduction("Asso:".$asso->getName().":ShortDesc");
            $asso->setDescriptionShortTraduction($descriptionShortTraduction);
            $manager->persist($descriptionShortTraduction);

            $description = "<p>";
            $description .= str_repeat($faker->word.' ', 9);
            $description .= "</p>";
            $descriptionShortTraduction->setFrench($description);
            $descriptionShortTraduction->setEnglish($description);
            $descriptionShortTraduction->setSpanish($description);
            $descriptionShortTraduction->setGerman($description);
            $descriptionShortTraduction->setChinese($description);


            //Création d'une traduction pour la description
            $descriptionTraduction = new Traduction("Asso:".$asso->getName().":Desc");
            $asso->setDescriptionTraduction($descriptionTraduction);
            $manager->persist($descriptionTraduction);

            $description = "";
            for ($j=0; $j < 5; $j++) {
                $description .= "<p>";
                $description .= str_repeat($faker->word, 9);
                $description .= "</p>";
            }
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
    }
}
