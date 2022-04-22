<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserRepository;

class UserTest extends ApiTestCase
{

    public function testSomething(): void
    {
        $client = static::createClient([], [
            'CAS-LOGIN' => 'abc'
        ]);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findAll()[0];
        $client->loginUser($testUser);
        $response = $client->request('POST', '/users');

        //$response = $client->request('GET', '/users/');

        $this->assertResponseIsSuccessful();
        /*$this->assertMatchesJsonSchema([
            "type" => "object",
            "hydra:member" => [
                "type" => "array",
                "items" => [
                    "type" => "object",
                    "@id" => ["type" => "string"],
                    "@type" => ["type" => "string"],
                    "id" => ["type" => "string"],
                    "login" => ["type" => "string"],
                    "firstName" => ["type" => "string"],
                    "lastName" => ["type" => "string"],
                ]
            ]
        ]);*/
    }
}
