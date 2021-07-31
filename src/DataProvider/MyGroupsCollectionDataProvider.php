<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

/**
 * This class decorates the DataProvider for a GET request on a Group collection. It permits to filter groups where the user is part of.
 */
class MyGroupsCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $itemDataProvider;
    private $security;

    public function __construct(CollectionDataProviderInterface $collectionDataProvider, Security $security)
    {
        $this->collectionDataProvider = $collectionDataProvider;
        $this->security = $security;
    }

    /**
     * This method is used by Symfony to know if it has to call this DataProvider. This method returns true if the request is a GET on a Group collection with the 'my_groups' name.
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        $checkClass = Group::class === $resourceClass;
        $checkOperation = ('my_groups' === $operationName) && ('collection' === $context['operation_type']);

        return $checkClass && $checkOperation;
    }

    /**
     * The method called to modify the group collection when the "support" method returns true.
     * 
     * @param mixed $id
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        /** @var User $userLogged */
        $userLogged = $this->security->getUser();

        return $userLogged->getGroups();
    }
}
