<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\GitHubIssue;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GitHubIssueSeeder extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserSeeder::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //  Récupération des users
        $users = $manager->getRepository(User::class)->findAll();

        $issueNumber = 0;
        foreach ($users as $user) {
            //  5% de chance qu'un utilisateur crée une issue
            if ($faker->boolean(5)) {
                ++$issueNumber;

                $githubIssue = new GitHubIssue();
                $githubIssue->setUser($user);
                $githubIssue->setGitHubIssueId($issueNumber);
                $days = (new DateTime())->diff($user->getTimestamps()->getCreatedAt())->days;
                $githubIssue->setCreatedAt($faker->dateTimeBetween('-'.$days.' days'));

                $manager->persist($githubIssue);
            }
        }

        $manager->flush();
    }
}
