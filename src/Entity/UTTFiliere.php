<?php

namespace App\Entity;

use App\Repository\UTTFiliereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UTTFiliereRepository::class)
 * @ORM\Table(name="utt_filieres")
 */
class UTTFiliere
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=UTTBranche::class, inversedBy="filieres")
     * @ORM\JoinColumn(name="branche_code", referencedColumnName="code")
     */
    private $branche;

    /**
     * @ORM\ManyToOne(targetEntity=Translation::class)
     * @ORM\JoinColumn(name="description_traduction_code", referencedColumnName="code")
     */
    private $descriptionTranslation;

    /**
     * @ORM\OneToMany(targetEntity=UE::class, mappedBy="filiere")
     */
    private $UEs;

    public function __construct(string $code = null)
    {
        $this->code = $code;
        $this->UEs = new ArrayCollection();
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
        if ($this->UEs->removeElement($uE)) {
            // set the owning side to null (unless already changed)
            if ($uE->getFiliere() === $this) {
                $uE->setFiliere(null);
            }
        }

        return $this;
    }
}
