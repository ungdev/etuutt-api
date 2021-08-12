<?php

namespace App\Entity;

use App\Repository\TranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * The entity that contains the translations in french, english, spanish, german and chinese of the field that reference it.
 *
 * @ORM\Entity(repositoryClass=TranslationRepository::class)
 * @ORM\Table(name="translations")
 */
class Translation
{
    /**
     * The code of the traduction formed as follow : EntityName:(FieldName:)UniqueName
     * The element between () is optional.
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\Type("string")
     * @Assert\Length(max=100)
     * @Assert\Regex("/^[a-zA-Z0-9 _-]+:(?:[a-zA-Z0-9 _-]+:)?[a-zA-Z0-9 _-]+$/")
     */
    private $code;

    /**
     * The french translation of the element.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Type("string")
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
    ])]
    private $french;

    /**
     * The english translation of the element.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Type("string")
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
    ])]
    private $english;

    /**
     * The spanish translation of the element.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Type("string")
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
    ])]
    private $spanish;

    /**
     * The german translation of the element.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Type("string")
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
    ])]
    private $german;

    /**
     * The chinese translation of the element.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Type("string")
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
    ])]
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
