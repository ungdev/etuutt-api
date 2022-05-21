<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;

abstract class EtuUTTApiTestCase extends ApiTestCase
{

    protected EntityManager $em;
    protected User $user;

    protected function setUp(): void
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        (new ORMPurger($this->em))->purge();
        $this->user = new User();
        $this->user->setFirstName('test');
        $this->user->setLastName('test');
        $this->user->setLogin('test');
        $this->user->addRole('ROLE_ADMIN');
        $this->em->persist($this->user);
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