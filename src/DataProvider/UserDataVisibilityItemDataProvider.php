<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\DenormalizedIdentifiersAwareItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use App\Entity\UserAddress;
use App\Repository\GroupRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Security;

/**
 * This class decorates the DataProvider for a GET request on a User item. It permits to remove info of the target user when the logged user do not have the permission to access it.
 */
class UserDataVisibilityItemDataProvider implements DenormalizedIdentifiersAwareItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $itemDataProvider;
    private $security;
    private $groupPublic;

    public function __construct(ItemDataProviderInterface $itemDataProvider, Security $security, GroupRepository $groupRepo)
    {
        $this->itemDataProvider = $itemDataProvider;
        $this->security = $security;
        $this->groupPublic = $groupRepo->findOneBy(['name' => 'Public']);
    }

    /**
     * This method is used by Symfony to know if it has to call this DataProvider. This method returns true if the request is a GET on a User item.
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        $checkClass = User::class === $resourceClass;
        $checkOperation = ('get' === $operationName) && ('item' === $context['operation_type']);

        return $checkClass && $checkOperation;
    }

    /**
     * The method called to modify the user when the `support` method returns true.
     *
     * @param mixed $id
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        /** @var null|User $userToShow */
        $userToShow = $this->itemDataProvider->getItem($resourceClass, $id, $operationName, $context);
        $userLogged = $this->security->getUser();

        if (!$userToShow) {
            return null;
        }

        /** @var UserAddress $address */
        foreach ($userToShow->getAddresses()->getValues() as $key => $address) {
            if (!$this->canAccessInfo($userToShow, $address->getAddressVisibility(), $userLogged)) {
                $address->setStreet('');
                $address->setCity('');
                $address->setPostalCode('');
                $address->setCountry('');
            }
        }

        $infos = $userToShow->getInfos();
        if (!$this->canAccessInfo($userToShow, $infos->getSexVisibility(), $userLogged)) {
            $infos->setSex('');
        }
        if (!$this->canAccessInfo($userToShow, $infos->getBirthdayVisibility(), $userLogged)) {
            $infos->setBirthday(new DateTime('0000-01-01'));
        }
        if (!$this->canAccessInfo($userToShow, $infos->getNationalityVisibility(), $userLogged)) {
            $infos->setNationality('');
        }

        $mailsPhones = $userToShow->getMailsPhones();
        if (!$this->canAccessInfo($userToShow, $mailsPhones->getPhoneNumberVisibility(), $userLogged)) {
            $mailsPhones->setPhoneNumber('');
        }
        if (!$this->canAccessInfo($userToShow, $mailsPhones->getMailPersonalVisibility(), $userLogged)) {
            $mailsPhones->setMailPersonal('');
        }

        return $userToShow;
    }

    private function canAccessInfo(User $userToShow, Collection $fieldVisibility, User $userLogged): bool
    {
        $canAccess = false;

        //  A user has acces to his own data, the admin has access to the data, the public data can be access by any logged user.
        if ($userToShow->getId() === $userLogged->getId() || $this->security->isGranted('ROLE_ADMIN', $userLogged) || $fieldVisibility->contains($this->groupPublic)) {
            $canAccess = true;
        } else {
            //  If the user to show has shared his data with a group in which the logged user is, he can access the data.
            $userLoggedGroups = $userLogged->getGroups();

            //  Intersection of 2 arrays of object : https://stackoverflow.com/questions/2834607/array-intersect-for-object-array-php
            $groupsIntersection = array_uintersect($fieldVisibility->toArray(), $userLoggedGroups->toArray(), function ($a, $b) {
                return strcmp(spl_object_hash($a), spl_object_hash($b));
            });
            $canAccess = 0 !== \count($groupsIntersection);
        }

        return $canAccess;
    }
}
