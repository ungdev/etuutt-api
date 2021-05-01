<?php

namespace App\Entity;

use App\Repository\UserOtherAttributsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserOtherAttributsRepository::class)
 * @ORM\Table(name="user_other_attributs")
 */
class UserOtherAttributs
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\Choice({"bool", "int", "float", "string", "longtext", "date", "datetime"})
     */
    private $type;

    public function __construct(string $name = null)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
