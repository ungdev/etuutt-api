<?php

namespace App\Tests\Groups;

use App\DataFixtures\GroupSeeder;
use App\Entity\Group;
use App\Tests\EtuUTTApiTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 *
 * @coversNothing
 */
final class GetGroups extends EtuUTTApiTestCase
{
    public function testNotConnected()
    {
        $client = self::createClient();
        $client->request('GET', '/groups');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testNoParameter()
    {
        $this->loadFixtures(new GroupSeeder(minimumVisibleGroupCount: 11, maximumVisibleGroupCount: 19));
        $client = self::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $crawler = $client->request('GET', '/groups');
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertIsArray($response->{'hydra:member'});
        static::assertSame(10, \count($response->{'hydra:member'}));
        // We verify we didn't get the same user twice
        $alreadyFoundUsers = [];
        foreach ($response->{'hydra:member'} as $member) {
            static::assertNotContains($member->id, $alreadyFoundUsers);
            $alreadyFoundUsers[] = $member->id;
            $expectedResult = $this->em->getRepository(Group::class)->find($member->id);
            static::assertTrue($expectedResult->getIsVisible());
            static::assertSameGroupReadSome($expectedResult, $member);
        }
        $groupsCount = $this->em->getRepository(Group::class)->count(['isVisible' => true]);
        $totalPagesCount = ceil($groupsCount / 10);
        static::assertSame($groupsCount, $response->{'hydra:totalItems'});
        static::assertSame(1, preg_match('/^\\/groups\\?page=1$/', $response->{'hydra:view'}->{'@id'}));
        static::assertSame(1, preg_match('/^\\/groups\\?page=2$/', $response->{'hydra:view'}->{'hydra:next'}));
        static::assertSame(1, preg_match("/^\\/groups\\?page={$totalPagesCount}$/", $response->{'hydra:view'}->{'hydra:last'}));
    }

    public function testPageParameter()
    {
        $this->loadFixtures(new GroupSeeder(minimumVisibleGroupCount: 11));
        $client = self::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $groupsCount = $this->em->getRepository(Group::class)->count(['isVisible' => true]);
        $totalPagesCount = (int) ceil($groupsCount / 10);
        // We verify we didn't get the same user twice
        $alreadyFoundUsers = [];
        for ($i = 1; $i <= $totalPagesCount; ++$i) {
            $crawler = $client->request('GET', "/groups?page={$i}");
            $response = json_decode($crawler->getContent());
            static::assertResponseStatusCodeSame(Response::HTTP_OK);
            static::assertIsArray($response->{'hydra:member'});
            $expectedSize = $i === $totalPagesCount ? $groupsCount % 10 : 10;
            // $expectedSize might be equal to 0 if $groupsCount is a multiple of 10. In this case, there are 10 groups on the last page.
            if (0 === $expectedSize) {
                $expectedSize = 10;
            }
            static::assertSame($expectedSize, \count($response->{'hydra:member'}));
            foreach ($response->{'hydra:member'} as $member) {
                static::assertNotContains($member->id, $alreadyFoundUsers);
                $alreadyFoundUsers[] = $member->id;
                $expectedResult = $this->em->getRepository(Group::class)->find($member->id);
                static::assertTrue($expectedResult->getIsVisible());
                static::assertSameGroupReadSome($expectedResult, $member);
            }
            static::assertSame($groupsCount, $response->{'hydra:totalItems'});
            static::assertSame(1, preg_match("/^\\/groups\\?page={$i}$/", $response->{'hydra:view'}->{'@id'}));
            if (1 !== $i) {
                $prevPage = $i - 1;
                static::assertSame(1, preg_match("/^\\/groups\\?page={$prevPage}$/", $response->{'hydra:view'}->{'hydra:previous'}));
            }
            if ($i !== $totalPagesCount) {
                $nextPage = $i + 1;
                static::assertSame(1, preg_match("/^\\/groups\\?page={$nextPage}$/", $response->{'hydra:view'}->{'hydra:next'}));
            }
            static::assertSame(1, preg_match("/^\\/groups\\?page={$totalPagesCount}$/", $response->{'hydra:view'}->{'hydra:last'}));
        }
    }

    public function testInvalidCredentials(): void
    {
        $client = self::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'this does not exist']]);
        $client->request('GET', '/groups');
        static::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testOutOfRangeParameters(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        // We want at least 1 group in the db
        $group = new Group();
        $group->setName('foo');
        $group->setIsVisible(true);
        $this->em->persist($group);
        $this->em->flush();
        $client->request('GET', '/groups?page=0');
        static::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $crawler = $client->request('GET', '/groups?page=2');
        $response = json_decode($crawler->getContent());
        static::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertEmpty($response->{'hydra:member'});
    }

    public function testWrongTypeParameter(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        // We want at least 1 group in the db
        $group = new Group();
        $group->setName('foo');
        $group->setIsVisible(true);
        $this->em->persist($group);
        $this->em->flush();
        $crawler = $client->request('GET', '/groups?page=1.5');
        $response = json_decode($crawler->getContent());
        static::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertIsArray($response->{'hydra:member'});
        static::assertSame(1, \count($response->{'hydra:member'}));
        static::assertSameGroupReadSome($group, $response->{'hydra:member'}[0]);
        $client->request('GET', '/groups?page=abc');
        static::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
