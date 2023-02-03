<?php

namespace App\ApiPlatform;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

class SearchInNamesFilter extends AbstractFilter
{
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

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if ('name' !== $property) {
            return;
        }
        $alias = $queryBuilder->getRootAliases()[0];
        $infoAlias = $queryNameGenerator->generateJoinAlias('info');
        $valueParameter = $queryNameGenerator->generateParameterName('value');
        $queryBuilder
            ->innerJoin("{$alias}.infos", $infoAlias)
            ->andWhere("({$alias}.firstName LIKE :{$valueParameter} OR {$alias}.lastName LIKE :{$valueParameter} OR {$infoAlias}.nickname LIKE :{$valueParameter})")
            ->setParameter($valueParameter, "%{$value}%")
        ;
    }
}
