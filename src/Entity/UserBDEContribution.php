<?php

namespace App\Entity;

use App\Repository\UserBDEContributionRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserBDEContributionRepository::class)
 * @ORM\Table(name="user_bde_contributions")
 */
class UserBDEContribution
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     *
     * @Assert\Uuid(versions=4)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="BDEContributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Semester::class)
     * @ORM\JoinColumn(name="start_semester_code", referencedColumnName="code")
     */
    private $startSemester;

    /**
     * @ORM\ManyToOne(targetEntity=Semester::class)
     * @ORM\JoinColumn(name="end_semester_code", referencedColumnName="code")
     */
    private $endSemester;

    /**
     * @ORM\Column(type="date")
     *
     * @Assert\Date
     */
    private $start;

    /**
     * @ORM\Column(type="date")
     *
     * @Assert\Date
     */
    private $end;

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

    public function getStartSemester(): ?Semester
    {
        return $this->startSemester;
    }

    public function setStartSemester(?Semester $startSemester): self
    {
        $this->startSemester = $startSemester;

        return $this;
    }

    public function getEndSemester(): ?Semester
    {
        return $this->endSemester;
    }

    public function setEndSemester(?Semester $endSemester): self
    {
        $this->endSemester = $endSemester;

        return $this;
    }

    public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }
}
