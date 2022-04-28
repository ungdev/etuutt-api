<?php

namespace App\Tests\users;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Symfony\Component\HttpFoundation\Response;

class GetUsers extends ApiTestCase
{

    private $responseWithNoParameter = array();
    private $lastPage;

    public function testNotConnected() : void
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testNoParameter() : void
    {
        // TODO : update this code : we need to fill the database, and then run this test
        /*$client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'admin' ]]);
        $crawler = $client->request('GET', '/users');
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
        $this->lastPage = $matches['id'];*/
    }

    public function testParameter1() : void
    {
        // TODO : fill the database and then run this test
        /*$client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'admin' ]]);
        $crawler = $client->request('GET', '/users?page=1');
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
        $this->assertEquals($this->responseWithNoParameter['view:last'], $response->{'hydra:view'}->{'hydra:last'});*/
    }

    public function testAllParameters() : void
    {
        // TODO : fill the database and then run this test
        /*$client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'admin' ]]);
        for ($i = 1; $i <= $this->lastPage; $i++) {
            $client->request('GET', '/users?page='.$i);
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }*/
    }

    public function testOutOfRangeParameters() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'admin' ]]);
        $client->request('GET', '/users?page=0');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $crawler = $client->request('GET', '/users?page=100');  // TODO : use database filling and not hard code the value of parameter 'page'
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEmpty($response->{'hydra:member'});
    }

    public function testWrongTypeParameter() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'admin' ]]);
        $crawler = $client->request('GET', '/users?page=1.5');
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals('/users?page=1', $response->{'hydra:view'}->{'@id'});
        $client->request('GET', '/users?page=abc');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

}
