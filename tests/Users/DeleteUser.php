<?php

namespace App\Tests\Users;

use App\Entity\User;
use App\Tests\EtuUTTApiTestCase;
use Faker\Provider\Uuid;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 *
 * @coversNothing
 */
final class DeleteUser extends EtuUTTApiTestCase
{
    public function testNormal(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
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
            ->execute()
        ;
        static::assertEmpty($users);
    }

    public function testNoPermission(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test2']]);
        $this->user->removeRole('ROLE_ADMIN');
        // TODO : understand why this is needed, I really don't get it
        $this->em->merge($this->user);
        $this->em->flush();
        $tempUser = $this->createUser('foo', 'bar', 'foobar');
        // Test with non existing user
        $client->request('DELETE', '/users/'.Uuid::uuid());
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        // Test with existing user
        $client->request('DELETE', '/users/'.$tempUser->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $user = $this->em->getRepository(User::class)->find($tempUser->getId());
        static::assertNull($user->getTimestamps()->getDeletedAt());
    }

    public function testNotConnected(): void
    {
        $client = static::createClient();
        $testUser = $this->em->createQueryBuilder()
            ->select('user.id')
            ->from(User::class, 'user')
            ->where('user.login = \'test\'')
            ->getQuery()
            ->execute()
        ;
        $client->request('DELETE', '/users/'.$testUser[0]['id']->jsonSerialize());
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $client->request('DELETE', '/users/'.Uuid::uuid());
        // Ok, so I guess it does not change much that this is a 404, but I think it should be a 401.
        // It seems like, even if the user isn't connected, the user is still searched in the database.
        // This is probably due to the fact that the authenticator is able to authenticate or not the user only if there is the CAS-LOGIN header.
        // But at the same time we can't remove this condition, or else you would need to be connected to access anything on the API.
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testNonExistingUser(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $client->request('DELETE', '/users/'.Uuid::uuid());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testSQLInjection(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $client->request('DELETE', '/users/\'');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('DELETE', '/users/"');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
