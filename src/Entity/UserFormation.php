<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\UserFormationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * The entity related to a User to track the formation and the following method.
 */
#[ORM\Entity(repositoryClass: UserFormationRepository::class)]
#[ORM\Table(name: 'user_formations')]
#[ORM\HasLifecycleCallbacks]
class UserFormation
{
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation to the User.
     */
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'formation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The relation to the Formation.
     */
    #[ORM\ManyToOne(targetEntity: UTTFormation::class)]
    #[ORM\JoinColumn(name: 'formation_name', referencedColumnName: 'name')]
    private ?UTTFormation $formation = null;

    /**
     * The relation to the FollowingMethod.
     */
    #[ORM\ManyToOne(targetEntity: UTTFormationFollowingMethod::class)]
    #[ORM\JoinColumn(name: 'following_method_name', referencedColumnName: 'name')]
    private ?UTTFormationFollowingMethod $followingMethod = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUTTFormation(): ?UTTFormation
    {
        return $this->formation;
    }

    public function setUTTFormation(?UTTFormation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getFollowingMethod(): ?UTTFormationFollowingMethod
    {
        return $this->followingMethod;
    }

    public function setFollowingMethod(?UTTFormationFollowingMethod $followingMethod): self
    {
        $this->followingMethod = $followingMethod;

        return $this;
    }
}
