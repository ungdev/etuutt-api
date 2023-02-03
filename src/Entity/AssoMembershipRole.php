<?php

namespace App\Entity;

use App\Repository\AssoMemberRoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AssoMemberRoleRepository::class)
 *
 * @ORM\Table(name="asso_membership_roles")
 */
class AssoMembershipRole
{
    /**
     * The name of the role in the association (e.g. "president"), not necessary for members.
     *
     * @ORM\Id
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type("string")
     *
     * @Assert\Length(min=1, max=255)
     *
     * @Assert\Regex("/^[a-z_]{1,255}/")
     */
    private $name;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[SerializedName('description')]
    private $descriptionTranslation;

    public function __construct($name)
    {
        $this->name = $name;
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
