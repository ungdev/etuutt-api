<?php

namespace App\ApiPlatform;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

class UEFilter extends AbstractFilter
{
    public function getDescription(string $resourceClass): array
    {
        return [
            'ue[]' => [
                'property' => 'ue',
                'type' => 'array',
                'required' => false,
                'is_collection' => true,
            ],
        ];
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if ('ue' !== $property) {
            return;
        }
        $alias = $queryBuilder->getRootAliases()[0];
        $nowParameter = $queryNameGenerator->generateParameterName('now');
        foreach ($value as $ueCode) {
            $ueAlias = $queryNameGenerator->generateJoinAlias('UE');
            $ueSubscriptionAlias = $queryNameGenerator->generateJoinAlias('UEsSubscriptions');
            $semesterAlias = $queryNameGenerator->generateJoinAlias('Semester');
            $ueParameter = $queryNameGenerator->generateParameterName('ue');
            $queryBuilder->innerJoin("{$alias}.UEsSubscriptions", $ueSubscriptionAlias)
                ->innerJoin("{$ueSubscriptionAlias}.UE", $ueAlias)
                ->innerJoin("{$ueSubscriptionAlias}.semester", $semesterAlias)
                ->andWhere("{$ueAlias}.code = :{$ueParameter}")
                ->andWhere("{$semesterAlias}.start <= :{$nowParameter}")
                ->andWhere("{$semesterAlias}.end >= :{$nowParameter}")
                ->setParameter($ueParameter, $ueCode)
            ;
        }
        $queryBuilder->setParameter($nowParameter, new \DateTime());
    }
}
