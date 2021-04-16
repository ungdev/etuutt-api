<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Event;
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
        $categories = [];

        //Création de 50 catégories
        for ($i=0; $i < 50; $i++) {
            //Créations d'une catégorie
            $category = new Category();

            $name = "";
            for ($j=0; $j < $faker->numberBetween(5, 20); $j++) {
                $name .= $faker->randomLetter;
            }
            $category->setName($name);

            array_push($categories, $category);
            //On persiste la catégorie dans la base de données
            $manager->persist($category);
        }
        $manager->flush();

        //Attribution de catégories à des events

        //Récupération des events
        $eventRepository = $manager->getRepository(Event::class);
        $events = $eventRepository->findAll();

        foreach ($events as $event) {
            for ($i=0; $i < $faker->numberBetween(1, 3); $i++) {
                $event->addCategory($faker->randomElement($categories));
            }
        }
        $manager->flush();
    }
}
