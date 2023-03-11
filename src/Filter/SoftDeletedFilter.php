<?php

declare(strict_types=1);

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\FilterInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\User;
use App\Entity\UserTimestamps;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

final class SoftDeletedFilter implements FilterInterface
{
    /**
     * @var string
     */
    private const PARAMETER_NAME = 'softDeleted';

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function apply(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if (!\in_array(SoftDeletableTrait::class, class_uses($resourceClass), true) && User::class !== $resourceClass) {
            throw new \InvalidArgumentException(sprintf("Can't apply the SoftDeleted filter on a resource (%s) not implementing the SoftDeletableTrait.", $resourceClass));
        }

        //  Parameter not provided or not supported
        $wantSeeSoftDeleted = $this->normalizeValue($context['filters'][self::PARAMETER_NAME] ?? false);

        //  Only admin can see softDeleted
        $wantSeeSoftDeleted = $this->security->isGranted('ROLE_ADMIN') ? $wantSeeSoftDeleted : false;

        if (User::class === $resourceClass) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->innerJoin(UserTimestamps::class, 'u_t', Join::WITH, sprintf('%s.id = u_t.user', $rootAlias));
            $queryBuilder->andWhere(sprintf('u_t.deletedAt IS %sNULL', $wantSeeSoftDeleted ? 'NOT ' : ''));
        } else {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere(sprintf('%s.deletedAt IS %sNULL', $rootAlias, $wantSeeSoftDeleted ? 'NOT ' : ''));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return array{softDeleted: array{property: string, type: string, required: false, swagger: array{description: string, name: string, type: string}, openapi: array{description: string, name: string, type: string}}}
     */
    public function getDescription(string $resourceClass): array
    {
        $description = 'Filter soft deleted entities, only usable by Admin. By default, "false", "0" or no value returns items that are not soft deleted. "true" or "1" returns soft deleted items only.';

        return [
            self::PARAMETER_NAME => [
                'property' => self::PARAMETER_NAME,
                'type' => 'bool',
                'required' => false,
                'swagger' => [
                    'description' => $description,
                    'name' => self::PARAMETER_NAME,
                    'type' => 'bool',
                ],
                'openapi' => [
                    'description' => $description,
                    'name' => self::PARAMETER_NAME,
                    'type' => 'bool',
                ],
            ],
        ];
    }

    private function normalizeValue(mixed $value): ?bool
    {
        return \in_array($value, [true, 'true', '1', 1], true);
    }
}
