<?php

namespace App\Entity;

use App\Repository\TraductionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TraductionRepository::class)
 * @ORM\Table(name="traductions")
 */
class Traduction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\Regex("/^[a-zA-Z\d _-:]{1,100}$/")
     */
    private $code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $french;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $english;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $spanish;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $german;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $chinese;

    public function __construct(string $code = null)
    {
        $this->code = $code;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getFrench(): ?string
    {
        return $this->french;
    }

    public function setFrench(?string $french): self
    {
        $this->french = $french;

        return $this;
    }

    public function getEnglish(): ?string
    {
        return $this->english;
    }

    public function setEnglish(?string $english): self
    {
        $this->english = $english;

        return $this;
    }

    public function getSpanish(): ?string
    {
        return $this->spanish;
    }

    public function setSpanish(?string $spanish): self
    {
        $this->spanish = $spanish;

        return $this;
    }

    public function getGerman(): ?string
    {
        return $this->german;
    }

    public function setGerman(?string $german): self
    {
        $this->german = $german;

        return $this;
    }

    public function getChinese(): ?string
    {
        return $this->chinese;
    }

    public function setChinese(?string $chinese): self
    {
        $this->chinese = $chinese;

        return $this;
    }
}
