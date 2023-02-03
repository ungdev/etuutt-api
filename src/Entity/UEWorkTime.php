<?php

namespace App\Entity;

use App\Repository\UEWorkTimeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that stores the worktime of a UE.
 *
 * @ORM\Entity(repositoryClass=UEWorkTimeRepository::class)
 *
 * @ORM\Table(name="ue_work_times")
 */
class UEWorkTime
{
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="uuid", unique=true)
     *
     * @ORM\GeneratedValue(strategy="CUSTOM")
     *
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Assert\Uuid
     */
    private $id;

    /**
     * The UE related to this Worktime.
     *
     * @ORM\OneToOne(targetEntity=UE::class, inversedBy="workTime", cascade={"persist", "remove"})
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $UE;

    /**
     * The number of hours during the semester of CM (Cours Magistral).
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type("int")
     *
     * @Assert\PositiveOrZero
     */
    private $cm;

    /**
     * The number of hours during the semester of TD (Travaux Dirigés).
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type("int")
     *
     * @Assert\PositiveOrZero
     */
    private $td;

    /**
     * The number of hours during the semester of TP (Travaux Pratiques).
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type("int")
     *
     * @Assert\PositiveOrZero
     */
    private $tp;

    /**
     * The estimated number of hours during the semester of THE (Travail Hors Encadrement).
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type("int")
     *
     * @Assert\PositiveOrZero
     */
    private $the;

    /**
     * The estimated number of hours during the semester of project.
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type("int")
     *
     * @Assert\PositiveOrZero
     */
    private $projet;

    /**
     * The number of week that this internship has to lasts.
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type("int")
     *
     * @Assert\PositiveOrZero
     */
    private $internship;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

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
