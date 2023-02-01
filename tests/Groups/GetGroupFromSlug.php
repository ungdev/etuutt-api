<?php

namespace App\Tests\Groups;

use App\DataFixtures\GroupSeeder;
use App\Entity\Group;
use App\Tests\EtuUTTApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetGroupFromSlug extends EtuUTTApiTestCase
{

    public function testNormal(): void
    {
        static::loadFixtures(new GroupSeeder());
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $group = $this->em->getRepository(Group::class)->findAll()[3];
        $crawler = $client->request('GET', '/groups/'.$group->getSlug());
        $response = json_decode($crawler->getContent());
        static::assertResponseStatusCodeSame(200);
        static::assertSameGroupReadOne($group, $response);
    }

    public function testNotConnected(): void
    {
        static::loadFixtures(new GroupSeeder());
        $client = static::createClient();
        $client->request('GET', '/groups/Public');
        $this->assertResponseStatusCodeSame(401);
    }

    public function testNotFound(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $client->request('GET', '/groups/foobar');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testSqlInjection(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $client->request('GET', '/groups/\'');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('GET', '/groups/"');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

}
