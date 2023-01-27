<?php

namespace App\ApiPlatform;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Repository\SemesterRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyInfo\Type;

class UEFilter extends AbstractContextAwareFilter
{

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($property !== 'ue') {
            return;
        }
        $alias = $queryBuilder->getRootAliases()[0];
        foreach ($value as $ueCode) {
            $ueAlias = $queryNameGenerator->generateJoinAlias('UE');
            $ueSubscriptionAlias = $queryNameGenerator->generateJoinAlias('UEsSubscriptions');
            $semesterAlias = $queryNameGenerator->generateJoinAlias('Semester');
            $queryBuilder->innerJoin("{$alias}.UEsSubscriptions", $ueSubscriptionAlias)
                ->innerJoin("${ueSubscriptionAlias}.UE", $ueAlias)
                ->innerJoin("${ueSubscriptionAlias}.semester", $semesterAlias)
                ->andWhere("{$ueAlias}.code = '{$ueCode}'")
                ->andWhere("{$semesterAlias}.start <= :now")
                ->andWhere("{$semesterAlias}.end >= :now");
        }
        $queryBuilder->setParameter('now', new \DateTime());
    }

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
}