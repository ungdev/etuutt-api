<?php

namespace App\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserAddress;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * This class decorates the DataProvider for a GET request on a User item. It permits to remove info of the target user when the logged user do not have the permission to access it.
 */
class UserDataVisibilityItemDataProvider implements ProviderInterface
{
    private ProviderInterface $itemDataProvider;
    private Security $security;
    private ?Group $groupPublic;

    public function __construct(ProviderInterface $itemDataProvider, Security $security, GroupRepository $groupRepo)
    {
        $this->itemDataProvider = $itemDataProvider;

        $this->security = $security;
        $this->groupPublic = $groupRepo->findOneBy(['name' => 'Public']);
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var null|User $userToShow */
        $userToShow = $this->itemDataProvider->provide($operation, $uriVariables, $context);

        /** @var null|User $userLogged */
        $userLogged = $this->security->getUser();

        if (!$userToShow) {
            return null;
        }

        if (!$userLogged) {
            // We don't want to return null because it would return a 404
            return new User();
        }

        /** @var UserAddress $address */
        foreach ($userToShow->getAddresses()->getValues() as $address) {
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
            $infos->setBirthday(new \DateTime('0000-01-01'));
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

        //  A user has access to his own data, the admin has access to the data, the public data can be access by any logged user.
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
