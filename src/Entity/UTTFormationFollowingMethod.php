<?php

namespace App\Entity;

use App\Repository\UTTFormationFollowingMethodRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that represents a way to follow a Formation at the UTT.
 */
#[ORM\Entity(repositoryClass: UTTFormationFollowingMethodRepository::class)]
#[ORM\Table(name: 'utt_formations_following_methods')]
class UTTFormationFollowingMethod
{
    /**
     * The Translation object that contains the translation of the description.
     */
    #[SerializedName('description')]
    #[ORM\ManyToOne(targetEntity: Translation::class, cascade: ['persist', 'remove'])]
    private ?Translation $descriptionTranslation = null;

    public function __construct(
        #[Assert\Length(min: 1, max: 100)]
        #[ORM\Id]
        #[ORM\Column(type: Types::STRING, length: 100)]
        private readonly ?string $name = null
    ) {
        $this->setDescriptionTranslation(new Translation());
    }

    public function getName(): ?string
    {
        return $this->name;
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
