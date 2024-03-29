<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\UECreditRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that store the amount and the category of credits that a UE gives.
 */
#[ORM\Entity(repositoryClass: UECreditRepository::class)]
#[ORM\Table(name: 'ue_credits')]
class UECredit
{
    use UUIDTrait;

    /**
     * The relation to the UE the credits are for.
     */
    #[ORM\ManyToOne(targetEntity: UE::class, inversedBy: 'credits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $UE = null;

    /**
     * The relation to the category of the credits.
     */
    #[ORM\ManyToOne(targetEntity: UECreditCategory::class)]
    #[ORM\JoinColumn(name: 'category_code', referencedColumnName: 'code')]
    private ?UECreditCategory $category = null;

    /**
     * The amount of credit.
     */
    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $credits = null;

    public function getUE(): ?UE
    {
        return $this->UE;
    }

    public function setUE(?UE $UE): self
    {
        $this->UE = $UE;

        return $this;
    }

    public function getCategory(): ?UECreditCategory
    {
        return $this->category;
    }

    public function setCategory(?UECreditCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    public function setCredits(?int $credits): self
    {
        $this->credits = $credits;

        return $this;
    }
}
