<?php

namespace App\Tests\Users;

use App\Entity\User;
use App\Tests\EtuUTTApiTestCase;
use Faker\Provider\Uuid;
use Symfony\Component\HttpFoundation\Response;

class DeleteUser extends EtuUTTApiTestCase
{

    public function testNormal() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test' ]]);
        $tempUser = new User();
        $tempUser->setLogin('foobar');
        $tempUser->setFirstName('foo');
        $tempUser->setLastName('bar');
        $this->em->persist($tempUser);
        $this->em->flush();
        $client->request('DELETE', '/users/'.$tempUser->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        $users = $this->em->createQueryBuilder()
            ->select('user.id')
            ->from(User::class, 'user')
            ->where('user.id=\''.$tempUser->getId().'\'')
            ->getQuery()
            ->execute();
        $this->assertEmpty($users);
    }

    public function testNoPermission() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test' ]]);
        $this->user->removeRole('ROLE_ADMIN');
        $tempUser = new User();
        $tempUser->setLogin('foobar');
        $tempUser->setFirstName('foo');
        $tempUser->setLastName('bar');
        $this->em->persist($tempUser);
        $this->em->flush();
        // Test with non existing user
        $client->request('DELETE', '/users/'.Uuid::uuid());
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        // Test with existing user
        $client->request('DELETE', '/users/'.$tempUser->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $users = $this->em->createQueryBuilder()
            ->select('user.id')
            ->from(User::class, 'user')
            ->where('user.id=\''.$tempUser->getId().'\'')
            ->getQuery()
            ->execute();
        $this->assertNotEmpty($users);
    }

    public function testNotConnected() : void
    {
        $client = static::createClient();
        $testUser = $this->em->createQueryBuilder()
            ->select('user.id')
            ->from(User::class, 'user')
            ->where('user.login = \'test\'')
            ->getQuery()
            ->execute();
        $client->request('DELETE', '/users/'.($testUser[0]['id']->jsonSerialize()));
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $client->request('DELETE', '/users/'.(Uuid::uuid()));
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testNonExistingUser() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test' ]]);
        $client->request('DELETE', '/users/'.Uuid::uuid());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testSQLInjection() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test' ]]);
        $client->request('DELETE', '/users/\'');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('DELETE', '/users/"');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

}
