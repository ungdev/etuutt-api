<?php

namespace App\ApiPlatform;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Group;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

/**
 * This class is automatically called by ApiPlatform. If a query is asking for a collection of Groups, this class hide groups with "isVisible" set to false for those who shall not see them.
 */
class FilterVisibleGroupExtension implements QueryCollectionExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (Group::class === $resourceClass && 'get' === $operationName) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere(sprintf('%s.isVisible = true', $rootAlias));
        }
    }
}
