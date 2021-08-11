<?php

namespace App\Security\Voter;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * The voter that get the argument of 'is-granted' and return a boolean to give acces or not to the ressource.
 */
class GroupAdminVoter extends Voter
{
    private $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * This method is used by Symfony to know if it has to call this Voter. This method returns a boolean based on the arguments given to 'is-granted'.
     */
    protected function supports($attribute, $subject): bool
    {
        $supportsAttribute = in_array($attribute, ['patch', 'delete']);
        $supportsSubject = $subject instanceof Group;
        $userLogged = $this->security->getUser() != null;

        return $supportsAttribute && $supportsSubject && $userLogged;
    }

    /**
     * If the 'supports' method returns true, this function is called to know if the access to the ressource is given or not.
     * 
     * @param string $attribute
     * @param Group $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $userLogged */
        $userLogged = $this->security->getUser();

        //  Admin users can do anything
        $isAdmin = $this->security->isGranted('ROLE_ADMIN');

        $isAdminOfGroup = $subject->getAdmins()->contains($userLogged);

        return $isAdmin || $isAdminOfGroup;
    }
}
