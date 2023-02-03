<?php

namespace App\Tests\Users;

use App\Tests\EtuUTTApiTestCase;
use Faker\Provider\Uuid;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 *
 * @coversNothing
 */
final class UpdateUser extends EtuUTTApiTestCase
{
    public function testNormal(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json']]);
        $testUser = $this->createUser('Foo', 'Bar', 'foobar');
        $testUserId = $testUser->getId();
        $testUserStudentId = $testUser->getStudentId();
        $testUserNationality = $testUser->getInfos()->getNationality();
        $testUserBirthday = $testUser->getInfos()->getBirthday()->format(\DateTimeInterface::RFC3339);
        $testUserAvatar = $testUser->getInfos()->getAvatar();
        $crawler = $client->request('PATCH', '/users/'.$testUser->getId(), ['body' => json_encode([
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
            ],
        ])]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = json_decode($crawler->getContent());
        // User checks
        static::assertSame($testUserId->jsonSerialize(), $response->{'id'});
        static::assertSame('foobar', $response->{'login'});
        static::assertSame($testUserStudentId, $response->{'studentId'});
        static::assertSame('Foo', $response->{'firstName'});
        static::assertSame('Bar', $response->{'lastName'});
        // socialNetwork checks
        static::assertSame('https://facebook.com/foobar', $response->{'socialNetwork'}->{'facebook'});
        static::assertSame('https://twitter.com/foobar', $response->{'socialNetwork'}->{'twitter'});
        static::assertSame('https://instagram.com/foobar', $response->{'socialNetwork'}->{'instagram'});
        static::assertSame('https://linkedin.com/foobar', $response->{'socialNetwork'}->{'linkedin'});
        static::assertSame('FooBar', $response->{'socialNetwork'}->{'pseudoDiscord'});
        static::assertTrue($response->{'socialNetwork'}->{'wantDiscordUTT'});
        // infos checks
        static::assertSame('Féminin', $response->{'infos'}->{'sex'});
        static::assertSame($testUserNationality, $response->{'infos'}->{'nationality'});
        static::assertSame($testUserBirthday, $response->{'infos'}->{'birthday'});
        static::assertSame($testUserAvatar, $response->{'infos'}->{'avatar'});
        static::assertSame('foobar', $response->{'infos'}->{'nickname'});
        static::assertSame('I don\'t have passions :(', $response->{'infos'}->{'passions'});
        static::assertSame('https://foobar.com', $response->{'infos'}->{'website'});
        // addresses checks
        static::assertCount(1, $response->{'addresses'});
        static::assertSame('Foobar Avenue', $response->{'addresses'}[0]->{'street'});
        static::assertSame('00 000', $response->{'addresses'}[0]->{'postalCode'});
        static::assertSame('Foobar City', $response->{'addresses'}[0]->{'city'});
        static::assertSame('United States of Foobar', $response->{'addresses'}[0]->{'country'});
        // mailsPhones checks
        static::assertSame('foo@bar.com', $response->{'mailsPhones'}->{'mailPersonal'});
        static::assertSame('01 23 45 67 89', $response->{'mailsPhones'}->{'phoneNumber'});
    }

    public function testNotConnected(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $client->request('PATCH', '/users/'.$this->user->getId(), ['body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $client->request('PATCH', '/users/'.Uuid::uuid(), ['body' => []]);
        // Strange this returns a 404, but it does not change much about security issues
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testNonExistingUser(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json']]);
        $client->request('PATCH', '/users/'.Uuid::uuid(), ['body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testNoParameter(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json']]);
        $client->request('PATCH', '/users/'.$this->user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testSQLInjection(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json']]);
        $testUser = $this->createUser('foo', 'bar', 'foobar');
        $client->request('PATCH', '/users/\'', ['body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/users/"', ['body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/users/'.$testUser->getId(), ['body' => ['socialNetwork' => ['facebook' => '\'']]]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        $client->request('PATCH', '/users/'.$testUser->getId(), ['body' => ['socialNetwork' => ['facebook' => '"']]]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }

    public function testInvalidFieldContent(): void
    {
        $client = static::createClient();
        $client->setDefaultOptions(['headers' => ['CAS-LOGIN' => 'test', 'Content-Type' => 'application/merge-patch+json']]);
        $testUser = $this->createUser('foo', 'bar', 'foobar');
        $client->request('PATCH', '/users/\'', ['body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/users/"', ['body' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $client->request('PATCH', '/users/'.$testUser->getId(), ['body' => ['socialNetwork' => ['facebook' => '\'']]]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        $client->request('PATCH', '/users/'.$testUser->getId(), ['body' => ['socialNetwork' => ['facebook' => '"']]]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }
}
