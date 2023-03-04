<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\UserUERepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * The entity that represents a subscription of a User to a UE during a Semester.
 */
#[ORM\Entity(repositoryClass: UserUERepository::class)]
#[ORM\Table(name: 'user_ue_subscriptions')]
#[ORM\HasLifecycleCallbacks]
class UserUESubscription
{
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation to the User which is subscribing to a UE.
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'UEsSubscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The relation to the UE that the User is subscribing to.
     */
    #[ORM\ManyToOne(targetEntity: UE::class, inversedBy: 'usersSubscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $UE = null;

    /**
     * The relation to the semester during which the subscription is made.
     */
    #[ORM\ManyToOne(targetEntity: Semester::class)]
    #[ORM\JoinColumn(name: 'semester_code', referencedColumnName: 'code')]
    private ?Semester $semester = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUE(): ?UE
    {
        return $this->UE;
    }

    public function setUE(?UE $UE): self
    {
        $this->UE = $UE;

        return $this;
    }

    public function getSemester(): ?Semester
    {
        return $this->semester;
    }

    public function setSemester(?Semester $semester): self
    {
        $this->semester = $semester;

        return $this;
    }
}
