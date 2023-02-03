<?php

namespace App\Tests\Users;

use App\DataFixtures\UserSeeder;
use App\Entity\User;
use App\Entity\UserAddress;
use App\Repository\UserRepository;
use App\Tests\EtuUTTApiTestCase;
use DateTimeInterface;
use Faker\Provider\Address;
use Faker\Provider\Uuid;
use Symfony\Component\HttpFoundation\Response;

class UpdateUser extends EtuUTTApiTestCase
{

    public function testNormal() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json' ]]);
        $testUser = $this->createUser('Foo', 'Bar', 'foobar');
        $testUserId = $testUser->getId();
        $testUserStudentId = $testUser->getStudentId();
        $testUserNationality = $testUser->getInfos()->getNationality();
        $testUserBirthday = $testUser->getInfos()->getBirthday()->format(DateTimeInterface::RFC3339);
        $testUserAvatar = $testUser->getInfos()->getAvatar();
        $crawler = $client->request('PATCH', '/users/'.$testUser->getId(), [ 'body' => json_encode([
            'socialNetwork' => [
                'facebook' => 'https://facebook.com/foobar',
                'twitter' => 'https://twitter.com/foobar',
                'instagram' => 'https://instagram.com/foobar',
                'linkedin' => 'https://linkedin.com/foobar',
                'pseudoDiscord' => 'FooBar',
                'wantDiscordUTT' => true,
            ],
            'RGPD' => [
                'isKeepingAccount' => true,
                'isDeletingEverything' => true,
            ],
            'preference' => [
                'birthdayDisplayOnlyAge' => false,
                'language' => 'en',
                'wantDaymail' => false,
                'wantDayNotif' => false,
            ],
            'infos' => [
                'sex' => 'Féminin',
                'nickname' => 'foobar',
                'passions' => 'I don\'t have passions :(',
                'website' => 'https://foobar.com',
            ],
            'addresses' => [
                [
                    'street' => 'Foobar Avenue',
                    'postalCode' => '00 000',
                    'city' => 'Foobar City',
                    'country' => 'United States of Foobar',
                ],
            ],
            'mailsPhones' => [
                'mailPersonal' => 'foo@bar.com',
                'phoneNumber' => '01 23 45 67 89',
            ]
        ])]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = json_decode($crawler->getContent());
        // User checks
        $this->assertEquals($testUserId->jsonSerialize(), $response->{'id'});
        $this->assertEquals('foobar', $response->{'login'});
        $this->assertEquals($testUserStudentId, $response->{'studentId'});
        $this->assertEquals('Foo', $response->{'firstName'});
        $this->assertEquals('Bar', $response->{'lastName'});
        // socialNetwork checks
        $this->assertEquals('https://facebook.com/foobar', $response->{'socialNetwork'}->{'facebook'});
        $this->assertEquals('https://twitter.com/foobar', $response->{'socialNetwork'}->{'twitter'});
        $this->assertEquals('https://instagram.com/foobar', $response->{'socialNetwork'}->{'instagram'});
        $this->assertEquals('https://linkedin.com/foobar', $response->{'socialNetwork'}->{'linkedin'});
        $this->assertEquals('FooBar', $response->{'socialNetwork'}->{'pseudoDiscord'});
        $this->assertEquals(true, $response->{'socialNetwork'}->{'wantDiscordUTT'});
        // infos checks
        $this->assertEquals('Féminin', $response->{'infos'}->{'sex'});
        $this->assertEquals($testUserNationality, $response->{'infos'}->{'nationality'});
        $this->assertEquals($testUserBirthday, $response->{'infos'}->{'birthday'});
        $this->assertEquals($testUserAvatar, $response->{'infos'}->{'avatar'});
        $this->assertEquals('foobar', $response->{'infos'}->{'nickname'});
        $this->assertEquals('I don\'t have passions :(', $response->{'infos'}->{'passions'});
        $this->assertEquals('https://foobar.com', $response->{'infos'}->{'website'});
        // addresses checks
        $this->assertCount(1, $response->{'addresses'});
        $this->assertEquals('Foobar Avenue', $response->{'addresses'}[0]->{'street'});
        $this->assertEquals('00 000', $response->{'addresses'}[0]->{'postalCode'});
        $this->assertEquals('Foobar City', $response->{'addresses'}[0]->{'city'});
        $this->assertEquals('United States of Foobar', $response->{'addresses'}[0]->{'country'});
        // mailsPhones checks
        $this->assertEquals('foo@bar.com', $response->{'mailsPhones'}->{'mailPersonal'});
        $this->assertEquals('01 23 45 67 89', $response->{'mailsPhones'}->{'phoneNumber'});
    }

    public function testNotConnected() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => ['Content-Type' => 'application/merge-patch+json' ]]);
        $client->request('PATCH', '/users/'.$this->user->getId(), [ 'body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $client->request('PATCH', '/users/'.Uuid::uuid(), [ 'body' => []]);
        // Strange this returns a 404, but it does not change much about security issues
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testNonExistingUser() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json' ]]);
        $client->request('PATCH', '/users/'.Uuid::uuid(), [ 'body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testNoParameter() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json' ]]);
        $client->request('PATCH', '/users/'.$this->user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testSQLInjection() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json' ]]);
        $testUser = $this->createUser('foo', 'bar', 'foobar');
        $client->request('PATCH', '/users/\'', [ 'body' => [] ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/users/"', [ 'body' => [] ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/users/'.$testUser->getId(), [ 'body' => [ 'socialNetwork' => ['facebook' => '\''] ]]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        $client->request('PATCH', '/users/'.$testUser->getId(), [ 'body' => [ 'socialNetwork' => ['facebook' => '"'] ]]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }

    public function testInvalidFieldContent() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json' ]]);
        $testUser = $this->createUser('foo', 'bar', 'foobar');
        $client->request('PATCH', '/users/\'', [ 'body' => [] ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/users/"', [ 'body' => [] ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/users/'.$testUser->getId(), [ 'body' => [ 'socialNetwork' => ['facebook' => '\''] ]]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        $client->request('PATCH', '/users/'.$testUser->getId(), [ 'body' => [ 'socialNetwork' => ['facebook' => '"'] ]]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }

}
