<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\TranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that contains the translations in french, english, spanish, german and chinese of the field that reference it.
 */
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
#[ORM\Table(name: 'translations')]
class Translation
{
    use UUIDTrait;

    /**
     * The french translation of the element.
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
        'group:write:create',
        'asso:read:one',
        'asso:read:some',
    ])]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $french = null;

    /**
     * The english translation of the element.
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
        'group:write:create',
        'asso:read:one',
        'asso:read:some',
    ])]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $english = null;

    /**
     * The spanish translation of the element.
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
        'group:write:create',
        'asso:read:one',
        'asso:read:some',
    ])]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $spanish = null;

    /**
     * The german translation of the element.
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
        'group:write:create',
        'asso:read:one',
        'asso:read:some',
    ])]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $german = null;

    /**
     * The chinese translation of the element.
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
        'group:write:create',
        'asso:read:one',
        'asso:read:some',
    ])]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $chinese = null;

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
