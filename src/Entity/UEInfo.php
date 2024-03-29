<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\UEInfoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * The entity that stores the additional info of a UE.
 */
#[ORM\Entity(repositoryClass: UEInfoRepository::class)]
#[ORM\Table(name: 'ue_infos')]
class UEInfo
{
    use UUIDTrait;

    /**
     * The relation to the UE related to this info.
     */
    #[ORM\OneToOne(targetEntity: UE::class, inversedBy: 'info', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $UE = null;

    /**
     * The degree in which the UE is available.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $degree = null;

    /**
     * The possible minor in which this UE is.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $minors = null;

    /**
     * The possible UE that are necessary to take this one.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $antecedent = null;

    /**
     * The languages spoken in ths UE, and their minimum level to understand.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $languages = null;

    /**
     * A field to leave a free comment.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    /**
     * The objectives of the UE.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $objectives = null;

    /**
     * The programme of this UE.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $programme = null;

    public function getUE(): ?UE
    {
        return $this->UE;
    }

    public function setUE(UE $UE): self
    {
        $this->UE = $UE;

        return $this;
    }

    public function getDegree(): ?string
    {
        return $this->degree;
    }

    public function setDegree(?string $degree): self
    {
        $this->degree = $degree;

        return $this;
    }

    public function getMinors(): ?string
    {
        return $this->minors;
    }

    public function setMinors(?string $minors): self
    {
        $this->minors = $minors;

        return $this;
    }

    public function getAntecedent(): ?string
    {
        return $this->antecedent;
    }

    public function setAntecedent(?string $antecedent): self
    {
        $this->antecedent = $antecedent;

        return $this;
    }

    public function getLanguages(): ?string
    {
        return $this->languages;
    }

    public function setLanguages(?string $languages): self
    {
        $this->languages = $languages;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getObjectives(): ?string
    {
        return $this->objectives;
    }

    public function setObjectives(?string $objectives): self
    {
        $this->objectives = $objectives;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(?string $programme): self
    {
        $this->programme = $programme;

        return $this;
    }
}
