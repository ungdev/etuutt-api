<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends ApiTestCase
{

    public function test_POST_users(): void
    {
        $client = static::createConnectedClient();
        $crawler = $client->request('GET', 'localhost:8000/users');
        $response = json_decode($crawler->getContent());
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsArray($response->{"hydra:member"});
        $this->assertNotEmpty($response->{"hydra:member"});
        foreach ($response->{"hydra:member"} as $member) {
            $this->assertMatchesRegularExpression("/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/", $member->{"id"});
            $this->assertNotEmpty($member->{"login"});
            $this->assertNotEmpty($member->{"firstName"});
            $this->assertNotEmpty($member->{"lastName"});
            $this->assertMatchesRegularExpression("/^https?:\\/\\/[\w.-]*\\.[\w-].+$/", $member->{"infos"}->{"avatar"});
            $this->assertIsArray($member->{"mailsPhones"});
        }
        $this->assertIsNumeric($response->{"hydra:totalItems"});
        $this->assertTrue($response->{"hydra:totalItems"} >= 0);
        $matches = array();
        $this->assertEquals(1, preg_match("/^\\/users\\?page=(?<id>\d+)+$/", $response->{"hydra:view"}->{"@id"}, $matches));
        $this->assertArrayHasKey("id", $matches);
        $this->assertEquals(1, preg_match("/^\\/users\\?page=(?<id>\d+)+$/", $response->{"hydra:view"}->{"hydra:next"}, $matches));
        $this->assertArrayHasKey("id", $matches);
        $this->assertEquals(1, preg_match("/^\\/users\\?page=(?<id>\d+)+$/", $response->{"hydra:view"}->{"hydra:last"}, $matches));
        $this->assertArrayHasKey("id", $matches);
    }

    private static function createConnectedClient(): Client
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'admin' ]]);
        return $client;
    }
}
