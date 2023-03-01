<?php

namespace App\Entity;

use App\Repository\UserUERepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that represents a subscription of a User to a UE during a Semester.
 *
 * @ORM\Entity(repositoryClass=UserUERepository::class)
 * @ORM\Table(name="user_ue_subscriptions")
 */
class UserUESubscription
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @Assert\Uuid
     */
    private ?Uuid $id = null;

    /**
     * The relation to the User which is subscribing to a UE.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="UEsSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * The relation to the UE that the User is subscribing to.
     *
     * @ORM\ManyToOne(targetEntity=UE::class, inversedBy="usersSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?UE $UE = null;

    /**
     * The relation to the semester during which the subscription is made.
     *
     * @ORM\ManyToOne(targetEntity=Semester::class)
     * @ORM\JoinColumn(name="semester_code", referencedColumnName="code")
     */
    private ?Semester $semester = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
