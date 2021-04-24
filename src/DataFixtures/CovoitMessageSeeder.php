<?php

namespace App\DataFixtures;

use App\Entity\Covoit;
use App\Entity\CovoitMessage;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CovoitMessageSeeder extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            CovoitSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        //Récupération des covoits et des users
        $covoitRepository = $manager->getRepository(Covoit::class);
        $covoits = $covoitRepository->findAll();
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();

        //Création de 100 covoitMessages
        for ($i = 0; $i < 100; ++$i) {
            //Créations d'un covoitMessage
            $covoitMessage = new CovoitMessage();

            //On lie le message à un covoit
            $covoitMessage->setCovoit($faker->randomElement($covoits));

            //On ajoute un user en tant qu'auteur du covoitMessage
            $covoitMessage->setAuthor($faker->randomElement($users));

            //Création du texte
            $text = '';
            for ($j = 0; $j < 5; ++$j) {
                $text .= '<p>';
                $text .= str_repeat($faker->word, 9);
                $text .= '</p>';
            }
            $covoitMessage->setText($text);

            //Création des timestamps
            $covoitMessage->setCreatedAt($faker->dateTimeBetween('-3 years'));
            $days = (new DateTime())->diff($covoitMessage->getCreatedAt())->days;
            $covoitMessage->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            //Soft delete aléatoire d'un Timestamps (Avec une chance de 10%)
            if ($faker->boolean(10)) {
                $days = (new DateTime())->diff($covoitMessage->getCreatedAt())->days;
                $covoitMessage->setDeletedAt($faker->dateTimeBetween('-'.$days.' days'));
            }

            //On persiste le covoitMessage dans la base de données
            $manager->persist($covoitMessage);
        }
        $manager->flush();
    }
}
