<?php

namespace App\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * This class decorates the DataProvider for a GET request on a Group collection. It permits to filter groups where the user is part of.
 */
class MyGroupsCollectionDataProvider implements ProviderInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * The method called to modify the group collection when the "support" method returns true.
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var User $userLogged */
        $userLogged = $this->security->getUser();

        return $userLogged->getGroups();
    }
}
