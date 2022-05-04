<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\UserSeeder;
use App\Entity\User;
use App\Entity\UserBan;
use App\Entity\UserBDEContribution;
use App\Entity\UserEtuUTTTeam;
use App\Entity\UserMailsPhones;
use App\Util\Slug;
use App\Util\Text;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Faker\Factory;

abstract class EtuUTTApiTestCase extends ApiTestCase
{

    protected EntityManager $em;

    protected function setUp(): void
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        (new ORMPurger($this->em))->purge();
        $user = new User();
        $user->setFirstName('test');
        $user->setLastName('test');
        $user->setLogin('test');
        $user->addRole('ROLE_ADMIN');
        $this->em->persist($user);
        $this->em->flush();
    }

    protected function loadFixtures(Fixture... $fixtures)
    {
        $fixtureLoader = new Loader();
        foreach ($fixtures as $fixture) {
            $fixtureLoader->addFixture($fixture);
        }
        foreach ($fixtureLoader->getFixtures() as $fixture) {
            $fixture->load($this->em);
        }
    }

}