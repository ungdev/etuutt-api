<?php

namespace App\Entity;

use App\Repository\UTTFormationFollowingMethodRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that represents a way to follow a Formation at the UTT.
 *
 * @ORM\Entity(repositoryClass=UTTFormationFollowingMethodRepository::class)
 * @ORM\Table(name="utt_formations_following_methods")
 */
class UTTFormationFollowingMethod
{
    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[SerializedName('description')]
    private ?Translation $descriptionTranslation = null;

    public function __construct(
        /**
         * @ORM\Id
         * @ORM\Column(type="string", length=100)
         * @Assert\Type("string")
         * @Assert\Length(min=1, max=100)
         */
        private ?string $name = null
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
