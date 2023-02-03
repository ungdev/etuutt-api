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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

/**
 * Base class for all tests of the project.
 * It provides some helper methods.
 */
abstract class EtuUTTApiTestCase extends ApiTestCase
{
    protected EntityManager $em;
    protected User $user;
    private array $databaseBackup;

    /**
     * Initializes $this->em and $this->user.
     * $this->user is an admin user. Its login is 'test'. Tests may use it to connect to the API.
     * It also purges the database.
     */
    protected function setUp(): void
    {
        $this->em = static::getContainer()->get('doctrine.orm.entity_manager');
        (new ORMPurger($this->em))->purge();
        $this->em->clear();
        $this->user = $this->createUser('test', 'test', 'test', 'ROLE_ADMIN');
    }

    /**
     * Asserts that the received group is the same as the expected one. It should have the layout defined by the {@see Groups} 'group:read:some' in the {@see Group} entity.
     *
     * @param Group $expected The group that should be received
     * @param mixed $actual   The group that was received (as an stdClass)
     */
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

    /**
     * Asserts that the received group is the same as the expected one. It should have the layout defined by the {@see Groups} 'group:read:one' in the {@see Group} entity.
     *
     * @param Group $expected The group that should be received
     * @param mixed $actual   The group that was received (as an stdClass)
     */
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

    /**
     * Asserts that the received translation is the same as the expected one.
     *
     * @param Translation $expected The translation that should be received
     * @param mixed       $actual   The translation that was received (as an stdClass)
     */
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

    /**
     * Asserts that the received user is the same as the expected one. It should have the layout defined by the {@see Groups} 'user:read:some' in the {@see User} entity.
     *
     * @param User  $expected The user that should be received
     * @param mixed $actual   The user that was received (as an stdClass)
     */
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

    /**
     * Asserts that the received user infos are the same as the expected one. It should have the layout defined by the {@see Groups} 'user:read:some' in the {@see UserInfos} entity.
     *
     * @param UserInfos $expected The user infos that should be received
     * @param mixed     $actual   The user infos that was received (as an stdClass)
     */
    protected static function assertSameUserInfosReadSome(UserInfos $expected, mixed $actual): void
    {
        static::assertIsObject($actual);
        static::assertSame(4, \count((array) $actual));
        static::assertSame('UserInfos', $actual->{'@type'});
        static::assertStringStartsWith('/.well-known/genid/', $actual->{'@id'});
        static::assertSame($expected->getAvatar(), $actual->avatar);
        static::assertSame($expected->getNickname(), $actual->nickname);
    }

    /**
     * Loads the given fixtures.
     *
     * @param Fixture ...$fixtures The fixtures to load
     */
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

    /**
     * Creates, persists, and returns a user. It can also flush the database if specified.
     *
     * @param string      $firstName The first name of the user
     * @param string      $lastName  The last name of the user
     * @param string      $login     The login of the user
     * @param null|string $role      The role of the user (defaults to 'ROLE_USER')
     * @param bool        $flush     Whether to flush the database or not (defaults to true)
     */
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

    /**
     * Backups the database. It sets the value of {@see $databaseBackup}. It should be used before the database is altered.
     * After changes, tests should use {@see assertDatabaseSameExcept} to assert that only specified fields have been modified.
     */
    protected function backupDatabase(): void
    {
        $this->databaseBackup = [];
        $this->_backupDatabase($this->databaseBackup);
    }

    /**
     * Asserts that the database is the same as the backup, except for the specified fields.
     *
     * @param array $diff The fields that should be different from the backup. It should be an array of the form:
     *
     * <code>
     *     [
     *         '<table_name>' => [
     *             'where' => [
     *                 '<field>' => '<value>',  // Changes are only expected where <field> have value <value>
     *             ],
     *             'diff' => [
     *                 '<modified_field>' => '<new_value>',
     *             ],
     *         ],
     *     ]
     * </code>
     * @param array $new The new entries that should be in the database. It should be an array of the form:
     *
     * <code>
     *     [
     *         '<table_name>' => [
     *             [
     *                 '<field>' => '<value>',
     *             ],  // Each array is a new entry
     *         ],
     * </code>
     */
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
        // Yes, that's dumb, but PHPFixer keeps changing it to assertSame
        \call_user_func_array([static::class, 'assertEquals'], [$this->databaseBackup, $actualDatabase]);
    }

    /**
     * Backups the database in the given array.
     *
     * @param array $backup A reference to the array in which the backup will be stored
     */
    private function _backupDatabase(array &$backup): void
    {
        $backup = [];
        // Fetch all tables
        $tables = $this->em->getConnection()->createSchemaManager()->listTables();
        foreach ($tables as $table) {
            $tableName = $table->getName();
            $backup[$tableName] = [];
            // Fetch all rows
            $rows = $this->em->getConnection()->prepare("SELECT * FROM {$tableName}")->executeQuery()->fetchAllAssociative();
            // We don't want to directly use the binary value of the UUIDs, but the more human-readable representation
            $getPrintableValue = function (string $column, $value) use ($table): ?string {
                $shouldConvert = UuidType::class === $table->getColumn($column)->getType()::class && null !== $value;

                return $shouldConvert ? Uuid::fromBinary($value)->jsonSerialize() : $value;
            };
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
