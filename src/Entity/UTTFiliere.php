<?php

namespace App\Entity;

use App\Repository\UTTFiliereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that represents a Filiere at the UTT.
 *
 * @ORM\Entity(repositoryClass=UTTFiliereRepository::class)
 * @ORM\Table(name="utt_filieres")
 */
class UTTFiliere
{
    /**
     * The code of the Filiere.
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=10)
     * @Assert\Type("string")
     * @Assert\Length(max=10)
     * @Assert\Regex("/^[A-Z\d]{1,10}$/")
     */
    private $code;

    /**
     * The complete name of the Filiere.
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=255)
     */
    private $name;

    /**
     * The relation to the Branche that contains this Filiere.
     *
     * @ORM\ManyToOne(targetEntity=UTTBranche::class, inversedBy="filieres")
     * @ORM\JoinColumn(name="branche_code", referencedColumnName="code")
     */
    private $branche;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[SerializedName('description')]
    private $descriptionTranslation;

    /**
     * The relation to all UEs contained in this Filiere.
     *
     * @ORM\OneToMany(targetEntity=UE::class, mappedBy="filiere")
     */
    private $UEs;

    public function __construct(string $code = null)
    {
        $this->code = $code;
        $this->UEs = new ArrayCollection();
        $this->setDescriptionTranslation(new Translation());
    }

    public function getCode(): ?string
    {
        return $this->code;
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

    public function getUTTBranche(): ?UTTBranche
    {
        return $this->branche;
    }

    public function setUTTBranche(?UTTBranche $branche): self
    {
        $this->branche = $branche;
        $branche->addUTTFiliere($this);

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

    /**
     * @return Collection|UE[]
     */
    public function getUEs(): Collection
    {
        return $this->UEs;
    }

    public function addUE(UE $uE): self
    {
        if (!$this->UEs->contains($uE)) {
            $this->UEs[] = $uE;
            $uE->setFiliere($this);
        }

        return $this;
    }

    public function removeUE(UE $uE): self
    {
        // set the owning side to null (unless already changed)
        if ($this->UEs->removeElement($uE) && $uE->getFiliere() === $this) {
            $uE->setFiliere(null);
        }

        return $this;
    }
}
