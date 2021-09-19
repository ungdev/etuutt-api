<?php

namespace App\Doctrine;

use App\Entity\Group;
use Symfony\Component\Security\Core\Security;

/**
 * A listener that activates when a Group entity is created. It sets the logged user to the admins of the group he creates, so that a group with no admin and no member could not be created.
 */
class GroupSetAdminAndMemberListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Group $group)
    {
        if ($this->security->getUser()) {
            $group->addAdmin($this->security->getUser());
            $group->addMember($this->security->getUser());
        }
    }
}
