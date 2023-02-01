<?php

namespace App\Tests\Groups;

use App\Entity\Group;
use App\Tests\EtuUTTApiTestCase;
use Faker\Provider\Uuid;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 *
 * @coversNothing
 */
final class UpdateGroup extends EtuUTTApiTestCase
{
    public function testNormal(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json']]);
        $group = $this->createGroup('test', false);
        $user = $this->createUser('foo', 'bar', 'foobar');
        $userAdmin = $this->createUser('imthe', 'admin', 'imtheadmin');
        // Changing the group there doesn't change the group in the database anymore (even if we flush)
        $this->em->detach($group);
        $group->getDescriptionTranslation()
            ->setFrench("sivouplé, tuez-moi, j'en ai marre des tests")
            ->setEnglish("please, kill me, I'm tired of testing")
            ->setSpanish('Por favor, matenme, estoy cansado de probar')
            ->setGerman("Bitte, töten Sie mich, ich bin müde zu testen (alors là j'ai aucune idée de si c'est comme ça que ça se dit, j'ai fait LV2 espagnol, mais copilot me dit que c'est comme ça)")
            ->setChinese('请，杀死我，我厌倦了测试 (oui, peut-être, au moins ça permet de tester les caractères utf-8 on va dire)')
        ;
        $group->setAvatar('https://thiscatdoesnotexist.com')
            ->setIsVisible(true)
            ->addMember($user)
            ->addMember($userAdmin)
            ->addAdmin($userAdmin)
        ;
        $group->setUpdatedAt(new \DateTime());
        $crawler = $client->request('PATCH', '/groups/'.$group->getSlug(), ['body' => json_encode([
            'description' => [
                'french' => $group->getDescriptionTranslation()->getFrench(),
                'english' => $group->getDescriptionTranslation()->getEnglish(),
                'spanish' => $group->getDescriptionTranslation()->getSpanish(),
                'german' => $group->getDescriptionTranslation()->getGerman(),
                'chinese' => $group->getDescriptionTranslation()->getChinese(),
            ],
            'avatar' => $group->getAvatar(),
            'isVisible' => $group->getIsVisible(),
            'members' => $group->getMembers()->map(function ($member) {
                return $member->getIdentifier();
            })->toArray(),
            'admins' => $group->getAdmins()->map(function ($admin) {
                return $admin->getIdentifier();
            })->toArray(),
        ])]);
        // TODO : do something about the updatedAt field, it doesn't always work depending on the second-subdivisions (ok, i get what i mean) we are executing the test
        // $group->setUpdatedAt(new \DateTime());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = json_decode($crawler->getContent());
        static::assertSameGroupReadOne($group, $response);
        // TODO : verify that the group has been updated in the database, i just don't get how doctrine works
        //$this->em->clear();
        //print("l'id c'est ".$group->getId());
        /*$dbGroup = $this->em->createQueryBuilder()
            ->select('g')
            ->from(Group::class, 'g')
            ->innerJoin('g.descriptionTranslation', 'd')
            // ->where('g.id = :id')
            // ->setParameter('id', $group->getId())
            ->getQuery()
            ->getArrayResult()
        ;*/
        //echo 'dans la db';
        //print_r($dbGroup);
        $dbGroup = $this->em->getRepository(Group::class)->findAll()[0];
        // echo "dans le truc de merde";
        //static::assertEquals($group, $dbGroup);
    }

    public function testNotConnected(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $group = $this->createGroup('group', true);
        $client->request('PATCH', '/groups/'.$group->getSlug(), ['body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $client->request('PATCH', '/groups/'.Uuid::uuid(), ['body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testNonExistingGroup(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json']]);
        $client->request('PATCH', '/groups/'.Uuid::uuid(), ['body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testNoBody(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json']]);
        $group = $this->createGroup('group', true);
        $client->request('PATCH', '/groups/'.$group->getSlug());
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testSQLInjection(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json']]);
        $group = $this->createGroup('group', true);
        $this->em->flush();
        $client->request('PATCH', "/groups/'", ['body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/groups/"', ['body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/groups/'.$group->getSlug(), ['body' => ['desciption' => ['french' => "'"]]]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        $client->request('PATCH', '/groups/'.$group->getSlug(), ['body' => ['description' => ['french' => '"']]]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }

    public function testInvalidFieldContent(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json']]);
        $group = $this->createGroup('group', true);
        $client->request('PATCH', '/groups/'.$group->getSlug(), ['body' => ['description' => 'definitely not an object :)']]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }

    protected function createGroup(string $name, bool $visibility, bool $flushDb = true): Group
    {
        $group = (new Group())
            ->setName($name)
            ->setIsVisible($visibility)
        ;
        $this->em->persist($group);
        if ($flushDb) {
            $this->em->flush();
        }

        return $group;
    }
}
