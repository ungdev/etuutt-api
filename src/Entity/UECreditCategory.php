<?php

namespace App\Entity;

use App\Repository\UECreditCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UECreditCategoryRepository::class)
 * @ORM\Table(name="ue_credits_categories")
 */
class UECreditCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct(string $code = null)
    {
        $this->code = $code;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
