<?php

namespace App\ApiPlatform;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\User;
use App\Entity\UserTimestamps;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

/**
 * This class is automatically called by ApiPlatform. If the class has a "deletedAt" property, it will keep the records where "deletedAt" is null.
 */
class FilterSoftDeletedExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $checkSoftDeletable = property_exists($resourceClass, 'deletedAt') || User::class === $resourceClass;
        $checkCanNotSeeDeleted = !$this->security->isGranted('ROLE_ADMIN');

        if ($checkSoftDeletable && $checkCanNotSeeDeleted) {
            switch ($resourceClass) {
                case User::class:
                    $rootAlias = $queryBuilder->getRootAliases()[0];
                    $queryBuilder->innerJoin(UserTimestamps::class, 'u_t', Join::WITH, sprintf('%s.id = u_t.user', $rootAlias));
                    $queryBuilder->andWhere('u_t.deletedAt IS NULL');

                    break;

                default:
                    $rootAlias = $queryBuilder->getRootAliases()[0];
                    $queryBuilder->andWhere(sprintf('%s.deletedAt IS NULL', $rootAlias));

                    break;
            }
        }
    }

    /**
     * We apply the same modifications to the query, no matter if it is a item or a collection operation.
     */
    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
    {
        $this->applyToCollection($queryBuilder, $queryNameGenerator, $resourceClass, $operationName);
    }
}
