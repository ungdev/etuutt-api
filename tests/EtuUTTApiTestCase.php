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
        $this->user = $this->createUser('test', 'test', 'test', 'ROLE_ADMIN');
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

    protected function createUser(string $firstName, string $lastName, string $login, ?string $role = 'ROLE_USER') : User
    {
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setLogin($login);
        $user->addRole($role);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

}