<?php

namespace App\Tests\Users;

use App\DataFixtures\UserSeeder;
use App\Entity\User;
use App\Tests\EtuUTTApiTestCase;
use Faker\Provider\Uuid;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 *
 * @coversNothing
 */
final class GetUserFromId extends EtuUTTApiTestCase
{
    public function testNormal(): void
    {
        $this->loadFixtures(new UserSeeder());
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $users = $this->em->createQueryBuilder()
            ->select('user.id, user.login, user.studentId, user.firstName, user.lastName,
                            socials.facebook, socials.twitter, socials.instagram, socials.linkedin, socials.pseudoDiscord, socials.wantDiscordUTT,
                            infos.sex, infos.nationality, infos.birthday, infos.avatar, infos.nickname, infos.passions, infos.website,
                            addresses.street, addresses.postalCode, addresses.city, addresses.country,
                            mailsPhones.mailPersonal, mailsPhones.phoneNumber')
            ->from(User::class, 'user')
            ->innerJoin('user.socialNetwork', 'socials')
            ->innerJoin('user.infos', 'infos')
            ->innerJoin('user.addresses', 'addresses')
            ->innerJoin('user.mailsPhones', 'mailsPhones')
            ->getQuery()
            ->execute()
        ;
        foreach ($users as $user) {
            $crawler = $client->request('GET', '/users/'.$user['id']->jsonSerialize());
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
            $response = json_decode($crawler->getContent());
            static::assertSame($user['id']->jsonSerialize(), $response->{'id'});
            static::assertSame($user['login'], $response->{'login'});
            static::assertSame($user['studentId'], $response->{'studentId'});
            static::assertSame($user['firstName'], $response->{'firstName'});
            static::assertSame($user['lastName'], $response->{'lastName'});
            static::assertSame($user['facebook'], $response->{'socialNetwork'}->{'facebook'});
            static::assertSame($user['twitter'], $response->{'socialNetwork'}->{'twitter'});
            static::assertSame($user['instagram'], $response->{'socialNetwork'}->{'instagram'});
            static::assertSame($user['linkedin'], $response->{'socialNetwork'}->{'linkedin'});
            static::assertSame($user['pseudoDiscord'], $response->{'socialNetwork'}->{'pseudoDiscord'});
            static::assertSame($user['wantDiscordUTT'], $response->{'socialNetwork'}->{'wantDiscordUTT'});
            static::assertSame($user['sex'], $response->{'infos'}->{'sex'});
            static::assertSame($user['nationality'], $response->{'infos'}->{'nationality'});
            // RFC3339 is the default normalization format of the date with symfony :
            // https://github.com/symfony/symfony/blob/60b1a2af0d819a98cde0b2144b3b22415f30d6c1/src/Symfony/Component/Serializer/Normalizer/DateTimeNormalizer.php#L29
            static::assertSame($user['birthday']->format(\DateTimeInterface::RFC3339), $response->{'infos'}->{'birthday'});
            static::assertSame($user['avatar'], $response->{'infos'}->{'avatar'});
            static::assertSame($user['nickname'], $response->{'infos'}->{'nickname'});
            static::assertSame($user['passions'], $response->{'infos'}->{'passions'});
            static::assertSame($user['website'], $response->{'infos'}->{'website'});
            static::assertSame($user['street'], $response->{'addresses'}[0]->{'street'});
            static::assertSame($user['postalCode'], $response->{'addresses'}[0]->{'postalCode'});
            static::assertSame($user['city'], $response->{'addresses'}[0]->{'city'});
            static::assertSame($user['country'], $response->{'addresses'}[0]->{'country'});
            static::assertSame($user['mailPersonal'], $response->{'mailsPhones'}->{'mailPersonal'});
            static::assertSame($user['phoneNumber'], $response->{'mailsPhones'}->{'phoneNumber'});
        }
    }

    public function testNotConnected(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users/'.$this->user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $client->request('GET', '/users/'.Uuid::uuid());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testNonExistingUser(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $client->request('GET', '/users/'.Uuid::uuid());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testSQLInjection(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test']]);
        $client->request('GET', '/users/\'');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('GET', '/users/"');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
