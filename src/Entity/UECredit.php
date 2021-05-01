<?php

namespace App\Entity;

use App\Repository\UECreditRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UECreditRepository::class)
 * @ORM\Table(name="ue_credits")
 */
class UECredit
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
     * @ORM\ManyToOne(targetEntity=UE::class, inversedBy="credits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UE;

    /**
     * @ORM\ManyToOne(targetEntity=UECreditCategory::class)
     * @ORM\JoinColumn(name="category_code", referencedColumnName="code")
     */
    private $category;

    /**
     * @ORM\Column(type="smallint")
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
