<?php

namespace App\Entity;

use App\Repository\UTTFormationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * The entity that represents a Formation at the UTT.
 * 
 * @ORM\Entity(repositoryClass=UTTFormationRepository::class)
 * @ORM\Table(name="utt_formations")
 */
class UTTFormation
{
    /**
     * The name of the Formation.
     * 
     * @ORM\Id
     * @ORM\Column(type="string", length=100)
     * 
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=100)
     */
    private $name;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class)
     * @ORM\JoinColumn(name="description_traduction_code", referencedColumnName="code")
     */
    private $descriptionTranslation;

    public function __construct(string $name = null)
    {
        $this->name = $name;
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
