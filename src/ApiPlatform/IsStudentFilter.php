<?php

namespace App\ApiPlatform;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

class IsStudentFilter extends AbstractFilter
{
    public function getDescription(string $resourceClass): array
    {
        return [
            'is_student' => [
                'property' => 'studentId',
                'type' => 'bool',
                'required' => false,
            ],
        ];
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if ('is_student' !== $property) {
            return;
        }
        $alias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere("{$alias}.studentId ".('true' === $value ? 'IS NOT' : 'IS').' NULL');
    }
}
