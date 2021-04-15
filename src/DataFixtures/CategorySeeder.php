<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\CategoryRepository;
use App\DataFixtures\EventSeeder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategorySeeder extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            EventSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        //Création de 50 catégories
        for ($i=0; $i < 50; $i++) {
            //Créations d'une catégorie
            $category = new Category();

            $name = "";
            for ($j=0; $j < $faker->numberBetween(5, 20); $j++) {
                $name .= $faker->randomLetter;
            }
            $category->setName($name);

            //On persiste la catégorie dans la base de données
            $manager->persist($category);
            $manager->flush();
        }

        //  Attribution de catégories à des events

        //  Récupération des assos et des mots-clé
        $eventRepository = $manager->getRepository(Event::class);
        $events = $eventRepository->findAll();
        $categoryRepository = $manager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        foreach ($events as $event) {
            for ($i=0; $i < $faker->numberBetween(1, 3); $i++) {
                $keyword = $faker->randomElement($categories);
                $event->addCategory($keyword);
            }
        }
        $manager->flush();
    }
}
