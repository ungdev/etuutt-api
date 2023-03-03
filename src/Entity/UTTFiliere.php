<?php

namespace App\Entity;

use App\Repository\UTTFiliereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that represents a Filiere at the UTT.
 */
#[ORM\Entity(repositoryClass: UTTFiliereRepository::class)]
#[ORM\Table(name: 'utt_filieres')]
class UTTFiliere
{
    /**
     * The complete name of the Filiere.
     */
    #[Assert\Type('string')]
    #[Assert\Length(min: 1, max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    /**
     * The relation to the Branche that contains this Filiere.
     */
    #[ORM\ManyToOne(targetEntity: UTTBranche::class, inversedBy: 'filieres')]
    #[ORM\JoinColumn(name: 'branche_code', referencedColumnName: 'code')]
    private ?UTTBranche $branche = null;

    /**
     * The Translation object that contains the translation of the description.
     */
    #[SerializedName('description')]
    #[ORM\ManyToOne(targetEntity: Translation::class, cascade: ['persist', 'remove'])]
    private ?Translation $descriptionTranslation = null;

    /**
     * The relation to all UEs contained in this Filiere.
     *
     * @var Collection<int, UE>|UE[]
     */
    #[ORM\OneToMany(targetEntity: UE::class, mappedBy: 'filiere')]
    private Collection $UEs;

    public function __construct(
        /**
         * The code of the Filiere.
         */
        #[Assert\Type('string')]
        #[Assert\Length(max: 10)]
        #[Assert\Regex('/^[A-Z\d]{1,10}$/')]
        #[ORM\Id]
        #[ORM\Column(type: Types::STRING, length: 10)]
        private readonly ?string $code = null
    ) {
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
