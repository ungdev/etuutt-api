<?php

namespace App\ApiPlatform;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\User;
use App\Entity\UserTimestamps;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * This class is automatically called by ApiPlatform when an entity is retrieved from the database. If the entity has a `deletedAt` property, it will keep the records where `deletedAt` is null.
 */
class FilterSoftDeletedExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(private readonly Security $security)
    {
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        $checkSoftDeletable = property_exists($resourceClass, 'deletedAt') || User::class === $resourceClass;
        $checkCanNotSeeDeleted = !$this->security->isGranted('ROLE_ADMIN');

        if ($checkSoftDeletable && $checkCanNotSeeDeleted) {
            if ($resourceClass == User::class) {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->innerJoin(UserTimestamps::class, 'u_t', Join::WITH, sprintf('%s.id = u_t.user', $rootAlias));
                $queryBuilder->andWhere('u_t.deletedAt IS NULL');
            } else {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf('%s.deletedAt IS NULL', $rootAlias));
            }
        }
    }

    /**
     * We apply the same modifications to the query, no matter if it is a item or a collection operation.
     */
    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        $this->applyToCollection($queryBuilder, $queryNameGenerator, $resourceClass, $operation);
    }
}
