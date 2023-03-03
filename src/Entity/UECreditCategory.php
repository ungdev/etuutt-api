<?php

namespace App\Entity;

use App\Repository\UECreditCategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The category of a credit.
 */
#[ORM\Entity(repositoryClass: UECreditCategoryRepository::class)]
#[ORM\Table(name: 'ue_credits_categories')]
class UECreditCategory
{
    /**
     * The meaning of the code.
     */
    #[Assert\Type('string')]
    #[Assert\Length(min: 1, max: 10)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    public function __construct(
        /**
         * The code of a category (e.g. 'CS', 'TM').
         */
        #[Assert\Type('string')]
        #[Assert\Length(min: 1, max: 10)]
        #[Assert\Regex('/^[A-Z]{1,10}$/')]
        #[ORM\Id]
        #[ORM\Column(type: Types::STRING, length: 10)]
        private readonly ?string $code = null
    ) {
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
