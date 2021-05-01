<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use App\Entity\Traduction;
use App\Entity\UE;
use App\Entity\UEComment;
use App\Entity\UECommentAnswer;
use App\Entity\UECommentReport;
use App\Entity\UECommentReportReason;
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
            //  Soft delete aléatoire d'un commentaire (Avec une chance de 2%)
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
        $upvotes = [];
        for ($i = 0; $i < 400; ++$i) {
            $upvote = new UECommentUpvote();
            $upvote->setComment($faker->randomElement($comments));
            $upvote->setUser($faker->randomElement($users));
            $upvote->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            //  Check si ce upvote existe déjà
            $alreadyIn = false;
            foreach ($upvotes as $savedUpvote) {
                if ($savedUpvote->getComment()->getId() === $upvote->getComment()->getId() && $savedUpvote->getUser()->getId() === $upvote->getUser()->getId()) {
                    $alreadyIn = true;
                }
            }
            if (!$alreadyIn) {
                $upvotes[] = $upvote;
                $manager->persist($upvote);
            }
        }
        $manager->flush();

        //  Création de 5 motifs de report
        for ($i = 0; $i < 5; ++$i) {
            $reportReason = new UECommentReportReason($faker->word.$faker->word);

            //  Création d'une traduction
            $descriptionTraduction = new Traduction('UECommentReportReason:'.$reportReason->getName());
            $reportReason->setDescriptionTraduction($descriptionTraduction);

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
            $manager->persist($reportReason);
        }
        $manager->flush();

        //  Création de 20 reports de commentaire
        $reportReasons = $manager->getRepository(UECommentReportReason::class)->findAll();
        for ($i = 0; $i < 20; ++$i) {
            $report = new UECommentReport();
            $report->setComment($faker->randomElement($comments));
            $report->setUser($faker->randomElement($users));
            $report->setReason($faker->randomElement($reportReasons));
            $body = '';
            for ($j = 0; $j < 5; ++$j) {
                $body .= '<p>';
                for ($k = 0; $k < 9; ++$k) {
                    $body .= $faker->word();
                }
                $body .= '</p>';
            }
            $report->setBody($body);
            $report->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            $manager->persist($report);
        }
        $manager->flush();
    }
}
