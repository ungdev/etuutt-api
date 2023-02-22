<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\UEStarCriterionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The criterions to rate a UE.
 *
 * @ORM\Entity(repositoryClass=UEStarCriterionRepository::class)
 * @ORM\Table(name="ue_stars_criterions")
 */
class UEStarCriterion
{
    use UUIDTrait;

    /**
     * The name of the criterion.
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=255)
     */
    private $name;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[SerializedName('description')]
    private $descriptionTranslation;

    public function __construct()
    {
        $this->setDescriptionTranslation(new Translation());
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

    public function getDescriptionTranslation(): ?Translation
    {
        return $this->descriptionTranslation;
    }

    public function setDescriptionTranslation(?Translation $descriptionTranslation): self
    {
        $this->descriptionTranslation = $descriptionTranslation;

        return $this;
    }
}
