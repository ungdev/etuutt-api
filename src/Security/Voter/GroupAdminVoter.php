<?php

namespace App\Security\Voter;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * The voter that get the argument of `is-granted()` and return a boolean to give access or not to the ressource. It allow admins of a group to update and delete it.
 */
class GroupAdminVoter extends Voter
{
    public function __construct(private readonly Security $security)
    {
    }

    /**
     * This method is used by Symfony to know if it has to call this Voter. This method returns a boolean based on the arguments given to `is-granted()`.
     *
     * @param mixed $attribute
     * @param mixed $subject
     */
    protected function supports($attribute, $subject): bool
    {
        $supportsAttribute = \in_array($attribute, ['patch', 'delete'], true);
        $supportsSubject = $subject instanceof Group;
        $userLogged = null !== $this->security->getUser();

        return $supportsAttribute && $supportsSubject && $userLogged;
    }

    /**
     * If the `supports` method returns true, this function is called to know if the access to the ressource is given or not.
     *
     * @param string $attribute
     * @param Group  $subject
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
