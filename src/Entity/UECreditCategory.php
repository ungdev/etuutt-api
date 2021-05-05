<?php

namespace App\Entity;

use App\Repository\UECreditCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The category of a credit.
 *
 * @ORM\Entity(repositoryClass=UECreditCategoryRepository::class)
 * @ORM\Table(name="ue_credits_categories")
 */
class UECreditCategory
{
    /**
     * The code of a category (e.g. 'CS', 'TM').
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=10)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=10)
     * @Assert\Regex("/^[A-Z]{1,10}$/")
     */
    private $code;

    /**
     * The meaning of the code.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=10)
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
