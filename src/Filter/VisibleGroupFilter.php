<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\FilterInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Group;
use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\User;
use App\Entity\UserTimestamps;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

final class VisibleGroupFilter implements FilterInterface
{
    /**
     * @var string
     */
    private const PARAMETER_NAME = 'isVisible';

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function apply(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if (Group::class !== $resourceClass) {
            throw new \InvalidArgumentException(sprintf("Can't apply the isVisible filter on a resource (%s) different from the Group entity.", $resourceClass));
        }

        //  Parameter not provided or not supported, default value to true
        $wantSeeInvisible = \in_array(
            $context['filters'][self::PARAMETER_NAME] ?? true,
            [false, 'false', '0', 0],
            true
        );

        //  Only admin can see Invisible groups
        $wantSeeInvisible = $this->security->isGranted('ROLE_ADMIN') ? $wantSeeInvisible : false;

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.isVisible = %s', $rootAlias, $wantSeeInvisible ? 'FALSE' : 'TRUE'));
    }

    /**
     * {@inheritdoc}
     *
     * @return array{isVisible: array{property: string, type: string, required: false, swagger: array{description: string, name: string, type: string}, openapi: array{description: string, name: string, type: string}}}
     */
    public function getDescription(string $resourceClass): array
    {
        $description = 'Filter invisible groups, only usable by Admin. By default, "true", "1" or no value returns items that are visible. "false" or "0" returns invisible groups only.';

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
}
