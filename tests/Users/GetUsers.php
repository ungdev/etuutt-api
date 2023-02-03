<?php

namespace App\Tests\Users;

use App\DataFixtures\BrancheFiliereFormationSeeder;
use App\DataFixtures\UESeeder;
use App\DataFixtures\UserInfoVisibilitySeeder;
use App\DataFixtures\UserSeeder;
use App\Entity\User;
use App\Entity\UserBranche;
use App\Tests\EtuUTTApiTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 *
 * @coversNothing
 */
final class GetUsers extends EtuUTTApiTestCase
{
    public function testNotConnected(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testNoParameter(): void
    {
        $this->loadFixtures(new UserSeeder());
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $crawler = $client->request('GET', '/users');
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertIsArray($response->{'hydra:member'});
        static::assertSame(10, \count($response->{'hydra:member'}));
        $expected = $this->em->getRepository(User::class)->findBy([], ['lastName' => 'ASC', 'firstName' => 'ASC'], 10);
        foreach ($response->{'hydra:member'} as $i => $member) {
            static::assertSameUserReadSome($expected[$i], $member);
        }
        static::assertIsNumeric($response->{'hydra:totalItems'});
        // 301 from the fixture, 1 from this test
        static::assertSame(302, $response->{'hydra:totalItems'});
        static::assertSame('/users?page=1', $response->{'hydra:view'}->{'@id'});
        static::assertSame('/users?page=2', $response->{'hydra:view'}->{'hydra:next'});
        static::assertSame('/users?page=31', $response->{'hydra:view'}->{'hydra:last'});
    }

    public function testPageParameter(): void
    {
        $this->loadFixtures(new UserSeeder());
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $expectedResults = $this->em->getRepository(User::class)->findBy([], ['lastName' => 'ASC', 'firstName' => 'ASC']);
        $lastPage = 31;
        $page = 0;
        foreach ($expectedResults as $i => $expectedResult) {
            if (0 === $i % 10) {
                ++$page;
                $crawler = $client->request('GET', '/users?page='.$page);
                $response = json_decode($crawler->getContent());
                $this->assertResponseStatusCodeSame(Response::HTTP_OK);
                static::assertSame(302, $response->{'hydra:totalItems'});
                static::assertSame('/users?page='.$page, $response->{'hydra:view'}->{'@id'});
                if ($page > 1) {
                    static::assertSame('/users?page='.($page - 1), $response->{'hydra:view'}->{'hydra:previous'});
                }
                if ($page < $lastPage) {
                    static::assertSame('/users?page='.($page + 1), $response->{'hydra:view'}->{'hydra:next'});
                }
                static::assertSame('/users?page='.$lastPage, $response->{'hydra:view'}->{'hydra:last'});
            }
            static::assertSameUserReadSome($expectedResult, $response->{'hydra:member'}[$i % 10]);
        }
    }

    public function testStudentIdParameter(): void
    {
        $this->loadFixtures(new UserSeeder(4));
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $expected = $this->em->getRepository(User::class)->findBy([], limit: 1, offset: 3)[0];
        $crawler = $client->request('GET', "/users?studentId={$expected->getStudentId()}");
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertIsArray($response->{'hydra:member'});
        static::assertSame(1, \count($response->{'hydra:member'}));
        static::assertSameUserReadSome($expected, $response->{'hydra:member'}[0]);
        static::assertSame(1, $response->{'hydra:totalItems'});
        static::assertSame("/users?studentId={$expected->getStudentId()}", $response->{'hydra:view'}->{'@id'});
    }

    public function testMailPersonalParameter(): void
    {
        $this->loadFixtures(new UserSeeder(4), new UserInfoVisibilitySeeder());
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $expected = $this->em->getRepository(User::class)->findBy([], limit: 1, offset: 3)[0];
        $crawler = $client->request('GET', '/users?mailsPhones.mailPersonal='.rawurlencode($expected->getMailsPhones()->getMailPersonal()));
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertIsArray($response->{'hydra:member'});
        static::assertSame(1, \count($response->{'hydra:member'}));
        static::assertSameUserReadSome($expected, $response->{'hydra:member'}[0]);
        static::assertSame(1, $response->{'hydra:totalItems'});
        static::assertSame('/users?mailsPhones.mailPersonal='.rawurlencode($expected->getMailsPhones()->getMailPersonal()), $response->{'hydra:view'}->{'@id'});
    }

    public function testPhoneNumberParameter(): void
    {
        $this->loadFixtures(new UserSeeder(4), new UserInfoVisibilitySeeder());
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $expected = $this->em->getRepository(User::class)->findBy([], limit: 1, offset: 3)[0];
        $crawler = $client->request('GET', '/users?mailsPhones.phoneNumber='.rawurlencode($expected->getMailsPhones()->getPhoneNumber()));
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertIsArray($response->{'hydra:member'});
        static::assertSame(1, \count($response->{'hydra:member'}));
        static::assertSameUserReadSome($expected, $response->{'hydra:member'}[0]);
        static::assertSame(1, $response->{'hydra:totalItems'});
        static::assertSame('/users?mailsPhones.phoneNumber='.rawurlencode($expected->getMailsPhones()->getPhoneNumber()), $response->{'hydra:view'}->{'@id'});
    }

    public function testBranchParameter(): void
    {
        $this->loadFixtures(new UserSeeder(4), new BrancheFiliereFormationSeeder());
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $branch = $this->em->getRepository(User::class)->findBy([], limit: 1, offset: 3)[0]
            ->getUTTBranche()
            ->getUTTBranche()
            ->getCode()
        ;
        // There may be a better way to do this
        // We basically catch UserBranche objects and then get the User object from it
        // And finally we need to sort everything
        $expectedBranches = $this->em->getRepository(UserBranche::class)->findBy(['branche' => ['code' => $branch]]);
        $expected = [];
        foreach ($expectedBranches as $expectedBranch) {
            $expected[] = $expectedBranch->getUser();
        }
        usort($expected, function (User $a, User $b) {
            // I'm beginning to love PHP. Hard to admit
            return strtoupper($a->getLastName()) <=> strtoupper($b->getLastName()) ?: strtoupper($a->getFirstName()) <=> strtoupper($b->getFirstName());
        });
        $crawler = $client->request('GET', "/users?branche.branche.code={$branch}");
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertIsArray($response->{'hydra:member'});
        static::assertSameSize($expected, $response->{'hydra:member'});
        foreach ($expected as $i => $user) {
            static::assertSameUserReadSome($user, $response->{'hydra:member'}[$i]);
        }
        static::assertSame(\count($expected), $response->{'hydra:totalItems'});
        static::assertSame("/users?branche.branche.code={$branch}", $response->{'hydra:view'}->{'@id'});
    }

    public function testFiliereParameter(): void
    {
        $this->loadFixtures(new UserSeeder(4), new BrancheFiliereFormationSeeder(1));
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $filiere = $this->em->createQueryBuilder()
            ->select('filiere.code')
            ->from(UserBranche::class, 'branche')
            ->innerJoin('branche.filiere', 'filiere')
            ->where('filiere.code IS NOT NULL')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        // There may be a better way to do this
        // We basically catch UserBranche objects and then get the User object from it
        // And finally we need to sort everything
        $expectedUserBranches = $this->em->getRepository(UserBranche::class)->findBy(['filiere' => ['code' => $filiere]]);
        $expected = [];
        foreach ($expectedUserBranches as $userBranch) {
            $expected[] = $userBranch->getUser();
        }
        usort($expected, function (User $a, User $b) {
            return strcasecmp($a->getLastName(), $b->getLastName()) ?: strcasecmp($a->getFirstName(), $b->getFirstName());
        });
        $crawler = $client->request('GET', "/users?branche.filiere.code={$filiere}");
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertIsArray($response->{'hydra:member'});
        static::assertSameSize($expected, $response->{'hydra:member'});
        foreach ($expected as $i => $user) {
            static::assertSameUserReadSome($user, $response->{'hydra:member'}[$i]);
        }
        static::assertSame(\count($expected), $response->{'hydra:totalItems'});
        static::assertSame("/users?branche.filiere.code={$filiere}", $response->{'hydra:view'}->{'@id'});
    }

    public function testSemesterParameter(): void
    {
        $this->loadFixtures(new UserSeeder(4), new BrancheFiliereFormationSeeder());
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $semester = $this->em->getRepository(User::class)->findBy([], limit: 1, offset: 3)[0]
            ->getUTTBranche()
            ->getSemesterNumber()
        ;
        $expectedUserBranches = $this->em->getRepository(UserBranche::class)->findBy(['semesterNumber' => $semester]);
        $expected = [];
        foreach ($expectedUserBranches as $userBranch) {
            $expected[] = $userBranch->getUser();
        }
        usort($expected, function (User $a, User $b) {
            return strcasecmp($a->getLastName(), $b->getLastName()) ?: strcasecmp($a->getFirstName(), $b->getFirstName());
        });
        $crawler = $client->request('GET', '/users?branche.semesterNumber='.$semester);
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertIsArray($response->{'hydra:member'});
        static::assertSameSize($expected, $response->{'hydra:member'});
        foreach ($expected as $i => $user) {
            static::assertSameUserReadSome($user, $response->{'hydra:member'}[$i]);
        }
        static::assertSame(\count($expected), $response->{'hydra:totalItems'});
        static::assertSame('/users?branche.semesterNumber='.$semester, $response->{'hydra:view'}->{'@id'});
    }

    public function testUEArrayParameterOneValue(): void
    {
        $this->loadFixtures(new UserSeeder(4), new UESeeder());
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $ue = $this->em->getRepository(User::class)->findBy([], limit: 1, offset: 3)[0]
            ->getUEsSubscriptions()[0]
            ->getUE()
            ->getCode()
        ;
        $users = $this->em->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->innerJoin('user.UEsSubscriptions', 'subscription')
            ->innerJoin('subscription.UE', 'ue')
            ->where('ue.code = :ue')
            ->setParameter('ue', $ue)
            ->orderBy('user.lastName', 'ASC')
            ->addOrderBy('user.firstName', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $crawler = $client->request('GET', '/users?ue[]='.$ue);
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertIsArray($response->{'hydra:member'});
        static::assertSameSize($users, $response->{'hydra:member'});
        foreach ($users as $i => $user) {
            static::assertSameUserReadSome($user, $response->{'hydra:member'}[$i]);
        }
        static::assertSame(\count($users), $response->{'hydra:totalItems'});
        static::assertSame('/users?ue%5B%5D='.$ue, $response->{'hydra:view'}->{'@id'});
    }

    public function testOutOfRangeParameters(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $client->request('GET', '/users?page=0');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $crawler = $client->request('GET', '/users?page=2');
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertEmpty($response->{'hydra:member'});
    }

    public function testWrongTypeParameter(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $crawler = $client->request('GET', '/users?page=2.5');
        $response = json_decode($crawler->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertEmpty($response->{'hydra:member'});
        $client->request('GET', '/users?page=abc');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
