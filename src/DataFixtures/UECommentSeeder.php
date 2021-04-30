<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use App\Entity\UE;
use App\Entity\UEComment;
use App\Entity\UECommentAnswer;
use App\Entity\UECommentUpvote;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UECommentSeeder extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserSeeder::class,
            UESeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();
        $ues = $manager->getRepository(UE::class)->findAll();
        $semesterRepo = $manager->getRepository(Semester::class);

        //  Création de 300 commentaires
        for ($i = 0; $i < 300; ++$i) {
            $comment = new UEComment();
            $comment->setUE($faker->randomElement($ues));
            $comment->setAuthor($faker->randomElement($users));
            $body = '';
            for ($j = 0; $j < 5; ++$j) {
                $body .= '<p>';
                for ($k = 0; $k < 9; ++$k) {
                    $body .= $faker->word();
                }
                $body .= '</p>';
            }
            $comment->setBody($body);
            $comment->setIsAnonymous($faker->boolean(10));
            $comment->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            $comment->setSemester($semesterRepo->getSemesterOfDate($comment->getCreatedAt()));
            $days = (new DateTime())->diff($comment->getCreatedAt())->days;
            $comment->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            //  Soft delete aléatoire d'un User (Avec une chance de 1%)
            if ($faker->boolean(2)) {
                $days = (new DateTime())->diff($comment->getUpdatedAt())->days;
                $comment->setDeletedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            }
            $manager->persist($comment);
        }
        $manager->flush();

        //  Créations de 200 réponses aux commentaires
        $comments = $manager->getRepository(UEComment::class)->findAll();
        for ($i = 0; $i < 200; ++$i) {
            $answer = new UECommentAnswer();
            $answer->setComment($faker->randomElement($comments));
            $answer->setAuthor($faker->randomElement($users));
            $body = '';
            for ($j = 0; $j < 5; ++$j) {
                $body .= '<p>';
                for ($k = 0; $k < 9; ++$k) {
                    $body .= $faker->word();
                }
                $body .= '</p>';
            }
            $answer->setBody($body);
            $answer->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            $days = (new DateTime())->diff($answer->getCreatedAt())->days;
            $answer->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            //  Soft delete aléatoire d'un User (Avec une chance de 1%)
            if ($faker->boolean(2)) {
                $days = (new DateTime())->diff($answer->getUpdatedAt())->days;
                $answer->setDeletedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            }
            $manager->persist($answer);
        }
        $manager->flush();

        //  Créations de 400 upvotes sur des commentaires
        for ($i = 0; $i < 400; ++$i) {
            try {
                $upvote = new UECommentUpvote();
                $upvote->setComment($faker->randomElement($comments));
                $upvote->setUser($faker->randomElement($users));
                $upvote->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
                $manager->persist($upvote);
            } catch (\Throwable $th) {
                //  On attrape l'erreur d'intégrité : Pas deux votes d'un user pour un commentaire
                //  => Couple user_id et comment_id unique
            }
        }
        $manager->flush();
    }
}
