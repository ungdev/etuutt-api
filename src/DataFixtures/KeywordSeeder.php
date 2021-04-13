<?php

namespace App\DataFixtures;

use App\Entity\Keyword;
use App\Entity\Asso;
use App\Repository\AssoRepository;
use App\Repository\KeywordRepository;
use App\DataFixtures\AssoSeeder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class KeywordSeeder extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            AssoSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        //Création de 100 mots-clés
        for ($i=0; $i < 100; $i++) {
            //Créations d'un mot-clé
            $keyword = new Keyword();

            $keyword->setName($faker->word());

            //On persiste le mot-clé dans la base de données
            $manager->persist($keyword);
            $manager->flush();
        }

        //  Attribution de mots-clé à des assos

        //  Récupération des assos et des mots-clé
        $assoRepository = $manager->getRepository(Asso::class);
        $assos = $assoRepository->findAll();
        $keywordsRepository = $manager->getRepository(Keyword::class);
        $keywords = $keywordsRepository->findAll();

        foreach ($assos as $asso) {
            for ($i=0; $i < $faker->numberBetween(0, 10); $i++) {
                $keyword = $faker->randomElement($keywords);
                $asso->addKeyword($keyword);
            }
        }
        $manager->flush();
    }
}
