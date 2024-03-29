<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\UEWorkTimeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that stores the worktime of a UE.
 */
#[ORM\Entity(repositoryClass: UEWorkTimeRepository::class)]
#[ORM\Table(name: 'ue_work_times')]
class UEWorkTime
{
    use UUIDTrait;

    /**
     * The UE related to this Worktime.
     */
    #[ORM\OneToOne(targetEntity: UE::class, inversedBy: 'workTime', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $UE = null;

    /**
     * The number of hours during the semester of CM (Cours Magistral).
     */
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $cm = null;

    /**
     * The number of hours during the semester of TD (Travaux Dirigés).
     */
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $td = null;

    /**
     * The number of hours during the semester of TP (Travaux Pratiques).
     */
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $tp = null;

    /**
     * The estimated number of hours during the semester of THE (Travail Hors Encadrement).
     */
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $the = null;

    /**
     * The estimated number of hours during the semester of project.
     */
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $projet = null;

    /**
     * The number of week that this internship has to lasts.
     */
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $internship = null;

    public function getUE(): ?UE
    {
        return $this->UE;
    }

    public function setUE(UE $UE): self
    {
        $this->UE = $UE;

        return $this;
    }

    public function getCm(): ?int
    {
        return $this->cm;
    }

    public function setCm(?int $cm): self
    {
        $this->cm = $cm;

        return $this;
    }

    public function getTd(): ?int
    {
        return $this->td;
    }

    public function setTd(?int $td): self
    {
        $this->td = $td;

        return $this;
    }

    public function getTp(): ?int
    {
        return $this->tp;
    }

    public function setTp(?int $tp): self
    {
        $this->tp = $tp;

        return $this;
    }

    public function getThe(): ?int
    {
        return $this->the;
    }

    public function setThe(?int $the): self
    {
        $this->the = $the;

        return $this;
    }

    public function getProjet(): ?int
    {
        return $this->projet;
    }

    public function setProjet(?int $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    public function getInternship(): ?int
    {
        return $this->internship;
    }

    public function setInternship(?int $internship): self
    {
        $this->internship = $internship;

        return $this;
    }
}
