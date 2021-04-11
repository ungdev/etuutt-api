<?php

namespace App\Entity;

use App\Repository\UserBrancheRepository;
use App\Entity\User;
use App\Entity\Branche;
use App\Entity\Filiere;
use App\Entity\Semester;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * @ORM\Entity(repositoryClass=UserBrancheRepository::class)
 * @ORM\Table(name="user_branches")
 */
class UserBranche
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     * 
     * @Assert\Uuid(versions = 4)
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="branche", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Branche::class)
     * @ORM\JoinColumn(name="branche_code", referencedColumnName="code")
     */
    private $branche;

    /**
     * @ORM\ManyToOne(targetEntity=Filiere::class)
     * @ORM\JoinColumn(name="filiere_code", referencedColumnName="code")
     */
    private $filiere;

    /**
     * @ORM\Column(type="smallint")
     */
    private $semesterNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Semester::class)
     * @ORM\JoinColumn(name="semester_code", referencedColumnName="code")
     */
    private $semester;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Assert\DateTime
     */
    private $createdAt;

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

    public function getBranche(): ?Branche
    {
        return $this->branche;
    }

    public function setBranche(?Branche $branche): self
    {
        $this->branche = $branche;

        return $this;
    }

    public function getFiliere(): ?Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?Filiere $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    public function getSemesterNumber(): ?int
    {
        return $this->semesterNumber;
    }

    public function setSemesterNumber(int $semesterNumber): self
    {
        $this->semesterNumber = $semesterNumber;

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
