<?php

namespace App\Entity;

use App\Repository\UECreditRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that store the amount and the category of credits that a UE gives.
 *
 * @ORM\Entity(repositoryClass=UECreditRepository::class)
 * @ORM\Table(name="ue_credits")
 */
class UECredit
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @Assert\Uuid
     */
    private $id;

    /**
     * The relation to the UE the credits are for.
     *
     * @ORM\ManyToOne(targetEntity=UE::class, inversedBy="credits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UE;

    /**
     * The relation to the category of the credits.
     *
     * @ORM\ManyToOne(targetEntity=UECreditCategory::class)
     * @ORM\JoinColumn(name="category_code", referencedColumnName="code")
     */
    private $category;

    /**
     * The amount of credit.
     *
     * @ORM\Column(type="smallint")
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private $credits;

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function setCredits(int $credits): self
    {
        $this->credits = $credits;

        return $this;
    }
}
