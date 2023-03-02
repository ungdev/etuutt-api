<?php

namespace App\Doctrine;

use App\Entity\Group;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * A listener that activates when a Group entity is created. It sets the logged user to the admins of the group he creates, so that a group with no admin and no member could not be created.
 */
class GroupSetAdminAndMemberListener
{
    public function __construct(private readonly Security $security)
    {
    }

    public function prePersist(Group $group): void
    {
        if ($this->security->getUser() !== null) {
            $group->addAdmin($this->security->getUser());
            $group->addMember($this->security->getUser());
        }
    }
}
