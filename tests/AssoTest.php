<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserRepository;

class AssoTest extends ApiTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(0);
        $client->loginUser($testUser);
        $response = static::createClient()->request('GET', '/assos/');

        //$this->assertResponseIsSuccessful();
        //$this->assertJsonContains(['@id' => '/']);
    }
}
