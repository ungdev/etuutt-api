<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Repository\UserFormationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity related to a User to track the formation and the following method.
 *
 * @ORM\Entity(repositoryClass=UserFormationRepository::class)
 * @ORM\Table(name="user_formations")
 */
class UserFormation
{
    use TimestampsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Assert\Uuid
     */
    private $id;

    /**
     * The relation to the User.
     *
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="formation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * The relation to the Formation.
     *
     * @ORM\ManyToOne(targetEntity=UTTFormation::class)
     * @ORM\JoinColumn(name="formation_name", referencedColumnName="name")
     */
    private $formation;

    /**
     * The relation to the FollowingMethod.
     *
     * @ORM\ManyToOne(targetEntity=UTTFormationFollowingMethod::class)
     * @ORM\JoinColumn(name="following_method_name", referencedColumnName="name")
     */
    private $followingMethod;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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
