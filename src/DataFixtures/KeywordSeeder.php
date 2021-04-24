<?php

namespace App\DataFixtures;

use App\Entity\Asso;
use App\Entity\Keyword;
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
        $faker = Factory::create('fr_FR');
        $keywords = [];

        //Création de 100 mots-clés
        for ($i = 0; $i < 100; ++$i) {
            //Créations d'un mot-clé
            $keyword = new Keyword();

            $keyword->setName(str_shuffle($faker->word.$faker->word));

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
