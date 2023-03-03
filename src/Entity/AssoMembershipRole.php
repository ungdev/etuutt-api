<?php

namespace App\Entity;

use App\Repository\AssoMemberRoleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssoMemberRoleRepository::class)]
#[ORM\Table(name: 'asso_membership_roles')]
class AssoMembershipRole
{
    /**
     * The Translation object that contains the translation of the description.
     */
    #[SerializedName('description')]
    #[ORM\ManyToOne(targetEntity: Translation::class, cascade: ['persist', 'remove'])]
    private ?Translation $descriptionTranslation = null;

    public function __construct(
        /**
         * The name of the role in the association (e.g. "president"), not necessary for members.
         */
        #[Assert\Type('string')]
        #[Assert\Length(min: 1, max: 255)]
        #[Assert\Regex('/^[a-z_]{1,255}/')]
        #[ORM\Id]
        #[ORM\Column(type: Types::STRING, length: 255)]
        private readonly ?string $name
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
