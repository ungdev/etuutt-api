<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Group;
use App\Entity\Translation;
use App\Entity\User;
use App\Entity\UserInfos;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

abstract class EtuUTTApiTestCase extends ApiTestCase
{
    protected EntityManager $em;
    protected User $user;
    private array $databaseBackup;

    protected function setUp(): void
    {
        $this->em = static::getContainer()->get('doctrine.orm.entity_manager');
        (new ORMPurger($this->em))->purge();
        $this->em->clear();
        $this->user = $this->createUser('test', 'test', 'test', 'ROLE_ADMIN');
    }

    protected static function assertSameGroupReadSome(Group $expected, mixed $actual): void
    {
        static::assertIsObject($actual);
        static::assertSame(10, \count((array) $actual));
        static::assertSame('group', $actual->{'@type'});
        static::assertSame("/groups/{$expected->getSlug()}", $actual->{'@id'});
        static::assertSame($expected->getId()->jsonSerialize(), $actual->id);
        static::assertSame($expected->getName(), $actual->name);
        static::assertSameTranslation($expected->getDescriptionTranslation(), $actual->description);
        static::assertSame($expected->getSlug(), $actual->slug);
        static::assertSame($expected->getAvatar(), $actual->avatar);
        static::assertSame($expected->getCreatedAt()->format(\DateTimeInterface::RFC3339), $actual->createdAt);
        static::assertSame($expected->getNumberOfMembers(), $actual->numberOfMembers);
    }

    protected static function assertSameGroupReadOne(Group $expected, mixed $actual): void
    {
        static::assertIsObject($actual);
        static::assertSame(13, \count((array) $actual));
        static::assertSame('/contexts/group', $actual->{'@context'});
        static::assertSame('group', $actual->{'@type'});
        static::assertSame("/groups/{$expected->getSlug()}", $actual->{'@id'});
        static::assertSame($expected->getId()->jsonSerialize(), $actual->id);
        static::assertSame($expected->getName(), $actual->name);
        static::assertSameTranslation($expected->getDescriptionTranslation(), $actual->description);
        static::assertSame(null === $expected->getAsso() ? null : '/assos/'.$expected->getAsso()->getId(), $actual->asso);
        static::assertSame($expected->getSlug(), $actual->slug);
        static::assertSame($expected->getAvatar(), $actual->avatar);
        static::assertSame($expected->getIsVisible(), $actual->isVisible);
        static::assertIsArray($actual->members);
        static::assertSame($expected->getNumberOfMembers(), \count($actual->members));
        foreach ($expected->getMembers() as $i => $member) {
            static::assertSame('/users/'.$member->getId(), $actual->members[$i]);
        }
        static::assertSame($expected->getCreatedAt()->format(\DateTimeInterface::RFC3339), $actual->createdAt);
        static::assertSame($expected->getUpdatedAt()->format(\DateTimeInterface::RFC3339), $actual->updatedAt);
    }

    protected static function assertSameTranslation(Translation $expected, mixed $actual): void
    {
        static::assertFalse(null === $expected xor null === $actual);
        static::assertIsObject($actual);
        static::assertSame(7, \count((array) $actual));
        static::assertSame('Translation', $actual->{'@type'});
        static::assertStringStartsWith('/.well-known/genid/', $actual->{'@id'});
        static::assertSame($expected->getFrench(), $actual->french);
        static::assertSame($expected->getEnglish(), $actual->english);
        static::assertSame($expected->getSpanish(), $actual->spanish);
        static::assertSame($expected->getGerman(), $actual->german);
        static::assertSame($expected->getChinese(), $actual->chinese);
    }

    protected static function assertSameUserReadSome(User $expected, mixed $actual): void
    {
        static::assertIsObject($actual);
        static::assertSame(7, \count((array) $actual));
        static::assertSame('user', $actual->{'@type'});
        static::assertSame("/users/{$expected->getId()}", $actual->{'@id'});
        static::assertSame($expected->getId()->jsonSerialize(), $actual->id);
        static::assertSame($expected->getFirstName(), $actual->firstName);
        static::assertSame($expected->getLastName(), $actual->lastName);
        static::assertSame($expected->getLogin(), $actual->login);
        static::assertSameUserInfosReadSome($expected->getInfos(), $actual->infos);
    }

    protected static function assertSameUserInfosReadSome(UserInfos $expected, mixed $actual): void
    {
        static::assertIsObject($actual);
        static::assertSame(4, \count((array) $actual));
        static::assertSame('UserInfos', $actual->{'@type'});
        static::assertStringStartsWith('/.well-known/genid/', $actual->{'@id'});
        static::assertSame($expected->getAvatar(), $actual->avatar);
        static::assertSame($expected->getNickname(), $actual->nickname);
    }

    protected function loadFixtures(Fixture ...$fixtures)
    {
        $fixtureLoader = new Loader();
        foreach ($fixtures as $fixture) {
            $fixtureLoader->addFixture($fixture);
        }
        foreach ($fixtureLoader->getFixtures() as $fixture) {
            $fixture->load($this->em);
        }
    }

    protected function createUser(string $firstName, string $lastName, string $login, ?string $role = 'ROLE_USER', bool $flush = true): User
    {
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setLogin($login);
        $user->addRole($role);
        $this->em->persist($user);
        if ($flush) {
            $this->em->flush();
        }

        return $user;
    }

    protected function backupDatabase(): void
    {
        $this->databaseBackup = [];
        $this->_backupDatabase($this->databaseBackup);
    }

    protected function assertDatabaseSameExcept(array $diff, array $new): void
    {
        $actualDatabase = [];
        $this->_backupDatabase($actualDatabase);
        foreach ($diff as $table => ['where' => $where, 'diff' => $delta]) {
            foreach ($this->databaseBackup[$table] as $i => $entry) {
                if (!array_diff_assoc($where, $entry)) {
                    foreach ($delta as $key => $value) {
                        $this->databaseBackup[$table][$i][$key] = $value;
                    }
                }
            }
        }
        foreach ($new as $table => $entries) {
            foreach ($entries as $entry) {
                $this->databaseBackup[$table][] = $entry;
            }
        }
        static::assertEquals($this->databaseBackup, $actualDatabase);
    }

    private function _backupDatabase(array &$backup): void
    {
        $backup = [];
        $tables = $this->em->getConnection()->createSchemaManager()->listTables();
        foreach ($tables as $table) {
            $tableName = $table->getName();
            $backup[$tableName] = [];
            $rows = $this->em->getConnection()->prepare("SELECT * FROM {$tableName}")->executeQuery()->fetchAllAssociative();
            $getPrintableValue = fn (string $column, $value): ?string => UuidType::class === $table->getColumn($column)->getType()::class && null !== $value ? Uuid::fromBinary($value)->jsonSerialize() : $value;
            foreach ($rows as $row) {
                // Convert all values to printable values
                foreach ($row as $column => &$value) {
                    $value = $getPrintableValue($column, $value);
                }
                // Store the row
                $backup[$tableName][] = $row;
            }
        }
    }
}
