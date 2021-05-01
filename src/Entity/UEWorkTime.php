<?php

namespace App\Entity;

use App\Repository\UEWorkTimeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UEWorkTimeRepository::class)
 * @ORM\Table(name="ue_work_time")
 */
class UEWorkTime
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
     * @ORM\OneToOne(targetEntity=UE::class, inversedBy="workTime", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $UE;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cm;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $td;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $the;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $projet;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stage;

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

    public function getStage(): ?int
    {
        return $this->stage;
    }

    public function setStage(?int $stage): self
    {
        $this->stage = $stage;

        return $this;
    }
}
