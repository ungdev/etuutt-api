<?php

namespace App\ApiPlatform;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

class SearchInNamesFilter extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($property !== 'name') {
            return;
        }
        $alias = $queryBuilder->getRootAliases()[0];
        $infoAlias = $queryNameGenerator->generateJoinAlias('info');
        $queryBuilder
            ->innerJoin("{$alias}.infos", $infoAlias)
            ->andWhere("({$alias}.firstName LIKE '%{$value}%' OR {$alias}.lastName LIKE '%{$value}%' OR {$infoAlias}.nickname LIKE '%{$value}%')");
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'name' => [
                'property' => 'name',
                'type' => 'string',
                'required' => false,
            ],
        ];
    }
}