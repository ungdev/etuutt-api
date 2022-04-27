<?php

namespace App\Tests\users;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Symfony\Component\HttpFoundation\Response;

class GetUsers extends ApiTestCase
{

    private $responseWithNoParameter = array();
    private $lastPage;

    public function test(): void
    {
        $client = static::createClient();
        $this->testNotConnected($client);
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'admin' ]]);
        $this->testNoParameter($client);
        $this->testParameter1($client);
        $this->testAllParameters($client);
        $this->testOutOfRangeParameters($client);
        $this->testWrongTypeParameter($client);
    }

    private function testNotConnected(Client $client) : void
    {
        $client->request('GET', 'localhost:8000/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    private function testNoParameter(Client $client) : void
    {
        $crawler = $client->request('GET', 'localhost:8000/users');
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsArray($response->{'hydra:member'});
        $this->assertNotEmpty($response->{'hydra:member'});
        $this->responseWithNoParameter['member'] = array();
        foreach ($response->{'hydra:member'} as $i => $member) {
            $this->assertMatchesRegularExpression("/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/", $member->{'id'});
            $this->assertNotEmpty($member->{'login'});
            $this->assertNotEmpty($member->{'firstName'});
            $this->assertNotEmpty($member->{'lastName'});
            $this->assertMatchesRegularExpression("/^https?:\\/\\/[\w.-]*\\.[\w-].+$/", $member->{'infos'}->{'avatar'});
            $this->assertIsArray($member->{'mailsPhones'});
            $returnedMember = array();
            $returnedMember['login'] = $member->{'login'};
            $returnedMember['firstName'] = $member->{'firstName'};
            $returnedMember['lastName'] = $member->{'lastName'};
            $returnedMember['avatar'] = $member->{'infos'}->{'avatar'};
            $this->responseWithNoParameter['member'][$i] = $returnedMember;
        }
        $this->assertIsNumeric($response->{'hydra:totalItems'});
        $this->assertTrue($response->{'hydra:totalItems'} >= 0);
        $this->responseWithNoParameter['totalItems'] = $response->{'hydra:totalItems'};
        $matches = array();
        $this->assertEquals(1, preg_match("/^\\/users\\?page=(?<id>\d+)+$/", $response->{'hydra:view'}->{'@id'}, $matches));
        $this->assertArrayHasKey('id', $matches);
        $this->responseWithNoParameter['view:id'] = $response->{'hydra:view'}->{'@id'};
        $this->assertEquals(1, preg_match("/^\\/users\\?page=(?<id>\d+)+$/", $response->{'hydra:view'}->{'hydra:next'}, $matches));
        $this->assertArrayHasKey('id', $matches);
        $this->responseWithNoParameter['view:next'] = $response->{'hydra:view'}->{'hydra:next'};
        $this->assertEquals(1, preg_match("/^\\/users\\?page=(?<id>\d+)+$/", $response->{'hydra:view'}->{'hydra:last'}, $matches));
        $this->assertArrayHasKey('id', $matches);
        $this->responseWithNoParameter['view:last'] = $response->{'hydra:view'}->{'hydra:last'};
        $this->lastPage = $matches['id'];
    }

    private function testParameter1(Client $client) : void
    {
        $crawler = $client->request('GET', 'localhost:8000/users?page=1');
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        foreach ($this->responseWithNoParameter['member'] as $i => $member) {
            $this->assertEquals($member['login'], $response->{'hydra:member'}[$i]->{'login'});
            $this->assertEquals($member['firstName'], $response->{'hydra:member'}[$i]->{'firstName'});
            $this->assertEquals($member['lastName'], $response->{'hydra:member'}[$i]->{'lastName'});
            $this->assertEquals($member['avatar'], $response->{'hydra:member'}[$i]->{'infos'}->{'avatar'});
        }
        $this->assertEquals($this->responseWithNoParameter['totalItems'], $response->{'hydra:totalItems'});
        $this->assertEquals($this->responseWithNoParameter['view:id'], $response->{'hydra:view'}->{'@id'});
        $this->assertEquals($this->responseWithNoParameter['view:next'], $response->{'hydra:view'}->{'hydra:next'});
        $this->assertEquals($this->responseWithNoParameter['view:last'], $response->{'hydra:view'}->{'hydra:last'});
    }

    private function testAllParameters(Client $client) : void
    {
        for ($i = 1; $i <= $this->lastPage; $i++) {
            $client->request('GET', 'localhost:8000/users?page='.$i);
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }
    }

    private function testOutOfRangeParameters(Client $client) : void
    {
        $client->request('GET', 'localhost:8000/users?page=0');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $crawler = $client->request('GET', 'localhost:8000/users?page='.$this->lastPage + 1);
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEmpty($response->{'hydra:member'});
    }

    private function testWrongTypeParameter(Client $client) : void
    {
        $crawler = $client->request('GET', 'localhost:8000/users?page=1.5');
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals('/users?page=1', $response->{'hydra:view'}->{'@id'});
        $client->request('GET', 'localhost:8000/users?page=abc');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

}
