<?php

namespace App\Doctrine;

use App\Entity\Group;
use Symfony\Component\Security\Core\Security;

/**
 * A listener that activates when a Group entity is created. It sets the logged user to the admins of the group he creates.
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
        dump($group);
        if ($this->security->getUser()) {
            $group->addAdmin($this->security->getUser());
            $group->addMember($this->security->getUser());
            dump($group);
        }
    }
}
