<?php

namespace App\Tests\users;

use App\DataFixtures\UserSeeder;
use App\Entity\User;
use App\Tests\EtuUTTApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetUsers extends EtuUTTApiTestCase
{

    public function testNotConnected() : void
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testNoParameter() : void
    {
        $this->loadFixtures(new UserSeeder());
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test' ]]);
        $crawler = $client->request('GET', '/users');
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsArray($response->{'hydra:member'});
        $this->assertNotEmpty($response->{'hydra:member'});
        $expectedResults = $this->em->createQueryBuilder()
            ->select('user.id, user.login, user.firstName, user.lastName, infos.avatar')
            ->from(User::class, 'user')
            ->innerJoin('user.infos', 'infos')
            ->addOrderBy('user.id')
            ->setMaxResults(10)
            ->getQuery()
            ->execute();
        foreach ($response->{'hydra:member'} as $i => $member) {
            $this->assertEquals($expectedResults[$i]['id']->jsonSerialize(), $member->{'id'});
            $this->assertEquals($expectedResults[$i]['login'], $member->{'login'});
            $this->assertNotEmpty($expectedResults[$i]['firstName'], $member->{'firstName'});
            $this->assertNotEmpty($expectedResults[$i]['lastName'], $member->{'lastName'});
            $this->assertEquals($expectedResults[$i]['avatar'], $member->{'infos'}->{'avatar'});
            $this->assertIsArray($member->{'mailsPhones'});
        }
        $this->assertIsNumeric($response->{'hydra:totalItems'});
        $this->assertTrue($response->{'hydra:totalItems'} >= 0);
        $matches = array();
        $this->assertEquals(1, preg_match("/^\\/users\\?page=(?<id>\d+)+$/", $response->{'hydra:view'}->{'@id'}, $matches));
        $this->assertArrayHasKey('id', $matches);
        $this->assertEquals(1, preg_match("/^\\/users\\?page=(?<id>\d+)+$/", $response->{'hydra:view'}->{'hydra:next'}, $matches));
        $this->assertArrayHasKey('id', $matches);
        $this->assertEquals(1, preg_match("/^\\/users\\?page=(?<id>\d+)+$/", $response->{'hydra:view'}->{'hydra:last'}, $matches));
        $this->assertArrayHasKey('id', $matches);
    }

    public function testPageParameter() : void
    {
        $this->loadFixtures(new UserSeeder());
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test' ]]);
        $expectedResults = $this->em->createQueryBuilder()
            ->select('user.id, user.login, user.firstName, user.lastName, infos.avatar')
            ->from(User::class, 'user')
            ->innerJoin('user.infos', 'infos')
            ->addOrderBy('user.id')
            ->getQuery()
            ->execute();
        $expectedResultsCount = count($expectedResults);
        $lastPage = (int) (($expectedResultsCount - 1) / 10) + 1;
        $page = 0;
        foreach ($expectedResults as $i => $expectedResult) {
            if ($i % 10 == 0) {
                $page++;
                $crawler = $client->request('GET', '/users?page='.$page);
                $response = json_decode($crawler->getContent());
                $this->assertResponseStatusCodeSame(Response::HTTP_OK);
                $this->assertEquals($expectedResultsCount, $response->{'hydra:totalItems'});
                $this->assertEquals("/users?page=".$page, $response->{'hydra:view'}->{'@id'});
                if ($page > 1) {
                    $this->assertEquals("/users?page=".($page - 1), $response->{'hydra:view'}->{'hydra:previous'});
                }
                if ($page < $lastPage) {
                    $this->assertEquals("/users?page=".($page + 1), $response->{'hydra:view'}->{'hydra:next'});
                }
                $this->assertEquals("/users?page=".$lastPage, $response->{'hydra:view'}->{'hydra:last'});
            }
            $this->assertEquals($expectedResult['login'], $response->{'hydra:member'}[$i%10]->{'login'});
            $this->assertEquals($expectedResult['id']->jsonSerialize(), $response->{'hydra:member'}[$i%10]->{'id'});
            $this->assertEquals($expectedResult['firstName'], $response->{'hydra:member'}[$i%10]->{'firstName'});
            $this->assertEquals($expectedResult['lastName'], $response->{'hydra:member'}[$i%10]->{'lastName'});
            $this->assertEquals($expectedResult['avatar'], $response->{'hydra:member'}[$i%10]->{'infos'}->{'avatar'});
        }

    }

    public function testOutOfRangeParameters() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test' ]]);
        $client->request('GET', '/users?page=0');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $crawler = $client->request('GET', '/users?page=2');
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEmpty($response->{'hydra:member'});
    }

    public function testWrongTypeParameter() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test' ]]);
        $crawler = $client->request('GET', '/users?page=2.5');
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEmpty($response->{'hydra:member'});
        $client->request('GET', '/users?page=abc');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

}
