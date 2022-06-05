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
        $testUser = $this->createUser('foo', 'bar', 'foobar');
        $testUserId = $testUser->getId();
        $testUserBirthday = $testUser->getInfos()->getBirthday();
        $crawler = $client->request('PATCH', '/users/'.$testUser->getId(), [ 'body' => json_encode([
            'socialNetwork' => [
                'facebook' => 'https://facebook.com/foobar',
                'twitter' => 'https://twitter.com/foobar',
                'instagram' => 'https://instagram.com/foobar',
                'linkedin' => 'https://linkedin.com/foobar',
                'pseudoDiscord' => 'foobar',
                'wantDiscordUTT' => true
            ],
            'RGPD' => [
                'isKeepingAccount' => true,
                'isDeletingEverything' => true
            ],
            'preferences' => [
                'birthdayDisplayOnlyAge' => false,
                'language' => 'en',
                'wantDaymail' => false,
                'wantDayNotif' => false
            ],
            'infos' => [
                'sex' => 'Féminin',
                'nickname' => 'foobar',
                'passions' => 'I have no passions :(',
                'website' => 'https://foobar.com'
            ],
            'addresses' => [
                [
                    'street' => 'avenue Foobar',
                    'postalCode' => '00 000',
                    'city' => 'Foobar City',
                    'country' => 'United States of Foobar'
                ]
            ],
            'mailsPhones' => [
                'mailPersonal' => 'foo@bar.com',
                'phoneNumber' => '0123456789'
            ]
        ])]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = json_decode($crawler->getContent());
        // User checks
        $this->assertEquals($testUserId, $response->{'id'});
        $this->assertEquals('foobar', $response->{'login'});
        $this->assertEquals(null, $response->{'studentId'});
        $this->assertEquals('foo', $response->{'firstName'});
        $this->assertEquals('bar', $response->{'lastName'});
        // Social network checks
        $this->assertEquals('https://facebook.com/foobar', $response->{'socialNetwork'}->{'facebook'});
        $this->assertEquals('https://twitter.com/foobar', $response->{'socialNetwork'}->{'twitter'});
        $this->assertEquals('https://instagram.com/foobar', $response->{'socialNetwork'}->{'instagram'});
        $this->assertEquals('https://linkedin.com/foobar', $response->{'socialNetwork'}->{'linkedin'});
        $this->assertEquals('foobar', $response->{'socialNetwork'}->{'pseudoDiscord'});
        $this->assertEquals(true, $response->{'socialNetwork'}->{'wantDiscordUTT'});
        // RGPD checks
        $this->assertEmpty($response->{'RGPD'});
        // Badges checks
        $this->assertEmpty($response->{'badges'});
        // Badges checks
        //$this->assertEmpty($response->{'branche'});
        // Formation checks
        //$this->assertEmpty($response->{'formation'});
        // Preference checks
        $this->assertEmpty($response->{'preference'});
        // Infos checks
        $this->assertEquals('Féminin', $response->{'infos'}->{'sex'});
        $this->assertNull($response->{'infos'}->{'nationality'});
        $this->assertEquals($testUserBirthday->format(DateTimeInterface::RFC3339), $response->{'infos'}->{'birthday'});
        $this->assertEquals('/default_user_avatar.png', $response->{'infos'}->{'avatar'});
        $this->assertEquals('foobar', $response->{'infos'}->{'nickname'});
        $this->assertEquals('I have no passions :(', $response->{'infos'}->{'passions'});
        $this->assertEquals('https://foobar.com', $response->{'infos'}->{'website'});
        // Addresses checks
        $this->assertEmpty($response->{'addresses'});
        $this->assertEquals(1, $response->{'addresses'}->length());
        $this->assertEquals("avenue Foobar", $response->{'addresses'}[0]->{'street'});
        $this->assertEquals("00 000", $response->{'addresses'}[0]->{'street'});
        $this->assertEquals("Foobar City", $response->{'addresses'}[0]->{'street'});
        $this->assertEquals("United States of Foobar", $response->{'addresses'}[0]->{'street'});
        // Mails-phones checks
        $this->assertEquals(1, $response->{'mailsPhones'}->length());
        $this->assertEquals('foo@bar.com', $response->{'mailsPhones'}->{'mailPersonal'});
        $this->assertEquals('0123456789', $response->{'mailsPhones'}->{'phoneNumber'});
    }

    public function testNotConnected() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => ['Content-Type' => 'application/merge-patch+json' ]]);
        $client->request('PATCH', '/users/'.$this->user->getId(), [ 'body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $client->request('PATCH', '/users/'.Uuid::uuid(), [ 'body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
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
        $client->request('PATCH', '/users/\'', [ 'body' => '{}' ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/users/"', [ 'body' => '{}' ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/users/'.$testUser->getId(), [ 'body' => json_encode([ 'socialNetwork' => ['facebook' => '\''] ])]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $client->request('PATCH', '/users/'.$testUser->getId(), [ 'body' => json_encode([ 'socialNetwork' => ['facebook' => '"'] ])]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testInvalidFieldContent() : void
    {
        $client = static::createClient();
        $client->setDefaultOptions([ 'headers' => [ 'CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json' ]]);
        $testUser = $this->createUser('foo', 'bar', 'foobar');
    }

}
