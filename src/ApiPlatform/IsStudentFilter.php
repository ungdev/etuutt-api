<?php

namespace App\ApiPlatform;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

class IsStudentFilter extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($property !== 'is_student') {
            return;
        }
        $alias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere("{$alias}.studentId ".($value == 'true' ? 'IS NOT' : 'IS').' NULL');
    }

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
}