<?php

namespace App\ApiPlatform;

use ApiPlatform\Api\IdentifiersExtractorInterface;
use ApiPlatform\Api\IriConverterInterface;
use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Common\Filter\SearchFilterTrait;
use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class SearchWithCustomParametersNamesFilter extends AbstractFilter implements SearchFilterInterface
{
    use SearchFilterTrait;

    protected array $aliasMap;
    private SearchFilter $searchFilter;

    public function __construct(ManagerRegistry $managerRegistry, IriConverterInterface $iriConverter, PropertyAccessorInterface $propertyAccessor = null, LoggerInterface $logger = null, array $properties = null, IdentifiersExtractorInterface $identifiersExtractor = null, array $aliasMap = null)
    {
        parent::__construct($managerRegistry, $logger, $properties);
        $this->aliasMap = $aliasMap;
        $this->searchFilter = new SearchFilter($managerRegistry, $iriConverter, $propertyAccessor, $logger, $properties, $identifiersExtractor);
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        $this->searchFilter->filterProperty($property, $value, $queryBuilder, $queryNameGenerator, $resourceClass, $operation, $context);
    }

    protected function denormalizePropertyName(int|string $property): string
    {
        return array_search($property, $this->aliasMap, strict: true) ?: $property;
    }

    protected function normalizePropertyName(int|string $property): string
    {
        return $this->aliasMap[$property] ?? $property;
    }

    protected function getType(string $doctrineType): string
    {
        return match ($doctrineType) {
            Types::ARRAY => 'array',
            Types::BIGINT, Types::INTEGER, Types::SMALLINT => 'int',
            Types::BOOLEAN => 'bool',
            Types::DATE_MUTABLE, Types::TIME_MUTABLE, Types::DATETIME_MUTABLE, Types::DATETIMETZ_MUTABLE, Types::DATE_IMMUTABLE, Types::TIME_IMMUTABLE, Types::DATETIME_IMMUTABLE, Types::DATETIMETZ_IMMUTABLE => \DateTimeInterface::class,
            Types::FLOAT => 'float',
            default => 'string',
        };
    }

    protected function getIriConverter(): IriConverterInterface
    {
        return $this->iriConverter;
    }

    protected function getPropertyAccessor(): PropertyAccessorInterface
    {
        return $this->propertyAccessor;
    }
}
