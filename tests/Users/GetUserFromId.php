<?php

namespace App\Tests\Users;

use App\DataFixtures\UserSeeder;
use App\Entity\User;
use App\Entity\UserAddress;
use App\Tests\EtuUTTApiTestCase;
use DateTimeInterface;
use Faker\Provider\Address;
use Faker\Provider\Uuid;
use Symfony\Component\HttpFoundation\Response;

class GetUserFromId extends EtuUTTApiTestCase
{

    public function testNormal() : void
    {
        $this->loadFixtures(new UserSeeder());
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test' ]]);
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
            ->execute();
        foreach ($users as $user) {
            $crawler = $client->request('GET', '/users/'.($user['id']->jsonSerialize()));
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
            $response = json_decode($crawler->getContent());
            $this->assertEquals($user['id']->jsonSerialize(), $response->{'id'});
            $this->assertEquals($user['login'], $response->{'login'});
            $this->assertEquals($user['studentId'], $response->{'studentId'});
            $this->assertEquals($user['firstName'], $response->{'firstName'});
            $this->assertEquals($user['lastName'], $response->{'lastName'});
            $this->assertEquals($user['facebook'], $response->{'socialNetwork'}->{'facebook'});
            $this->assertEquals($user['twitter'], $response->{'socialNetwork'}->{'twitter'});
            $this->assertEquals($user['instagram'], $response->{'socialNetwork'}->{'instagram'});
            $this->assertEquals($user['linkedin'], $response->{'socialNetwork'}->{'linkedin'});
            $this->assertEquals($user['pseudoDiscord'], $response->{'socialNetwork'}->{'pseudoDiscord'});
            $this->assertEquals($user['wantDiscordUTT'], $response->{'socialNetwork'}->{'wantDiscordUTT'});
            $this->assertEquals($user['sex'], $response->{'infos'}->{'sex'});
            $this->assertEquals($user['nationality'], $response->{'infos'}->{'nationality'});
            // RFC3339 is the default normalization format of the date with symfony :
            // https://github.com/symfony/symfony/blob/60b1a2af0d819a98cde0b2144b3b22415f30d6c1/src/Symfony/Component/Serializer/Normalizer/DateTimeNormalizer.php#L29
            $this->assertEquals($user['birthday']->format(DateTimeInterface::RFC3339), $response->{'infos'}->{'birthday'});
            $this->assertEquals($user['avatar'], $response->{'infos'}->{'avatar'});
            $this->assertEquals($user['nickname'], $response->{'infos'}->{'nickname'});
            $this->assertEquals($user['passions'], $response->{'infos'}->{'passions'});
            $this->assertEquals($user['website'], $response->{'infos'}->{'website'});
            $this->assertEquals($user['street'], $response->{'addresses'}[0]->{'street'});
            $this->assertEquals($user['postalCode'], $response->{'addresses'}[0]->{'postalCode'});
            $this->assertEquals($user['city'], $response->{'addresses'}[0]->{'city'});
            $this->assertEquals($user['country'], $response->{'addresses'}[0]->{'country'});
            $this->assertEquals($user['mailPersonal'], $response->{'mailsPhones'}->{'mailPersonal'});
            $this->assertEquals($user['phoneNumber'], $response->{'mailsPhones'}->{'phoneNumber'});
        }
    }

    public function testNotConnected() : void
    {
        $client = static::createClient();
        $client->request('GET', '/users/'.$this->user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $client->request('GET', '/users/'.(Uuid::uuid()));
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testNonExistingUser() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test' ]]);
        $client->request('GET', '/users/'.Uuid::uuid());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testSQLInjection() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test' ]]);
        $client->request('GET', '/users/\'');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('GET', '/users/"');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

}
