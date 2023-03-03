<?php

namespace App\Entity;

use App\Repository\UEInfoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that stores the additional info of a UE.
 */
#[ORM\Entity(repositoryClass: UEInfoRepository::class)]
#[ORM\Table(name: 'ue_infos')]
class UEInfo
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The relation to the UE related to this info.
     */
    #[ORM\OneToOne(targetEntity: UE::class, inversedBy: 'info', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $UE = null;

    /**
     * The degree in which the UE is available.
     */
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $degree = null;

    /**
     * The possible minor in which this UE is.
     */
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $minors = null;

    /**
     * The possible UE that are necessary to take this one.
     */
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $antecedent = null;

    /**
     * The languages spoken in ths UE, and their minimum level to understand.
     */
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $languages = null;

    /**
     * A field to leave a free comment.
     */
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    /**
     * The objectives of the UE.
     */
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $objectives = null;

    /**
     * The programme of this UE.
     */
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $programme = null;

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
