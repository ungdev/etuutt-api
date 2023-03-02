<?php

namespace App\Entity;

use App\Repository\UTTBrancheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that represents a Branche at the UTT.
 *
 * @ORM\Entity(repositoryClass=UTTBrancheRepository::class)
 * @ORM\Table(name="utt_branches")
 */
class UTTBranche
{
    /**
     * The complete name of the Branche.
     *
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\Type('string')]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $name = null;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[SerializedName('description')]
    private ?Translation $descriptionTranslation = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $exitSalary = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?int $employmentRate = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $CDIRate = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $abroadEmploymentRate = null;

    /**
     * @ORM\OneToMany(targetEntity=UTTFiliere::class, mappedBy="branche", orphanRemoval=true)
     */
    private Collection $filieres;

    public function __construct(
        /**
         * The code of the Branche.
         *
         * @ORM\Id
         * @ORM\Column(type="string", length=10)
         */
        #[Assert\Type('string')]
        #[Assert\Length(max: 10)]
        #[Assert\Regex('/^[A-Z\d]{1,10}$/')]
        private ?string $code = null
    ) {
        $this->setDescriptionTranslation(new Translation());
        $this->filieres = new ArrayCollection();
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

    public function getDescriptionTranslation(): ?Translation
    {
        return $this->descriptionTranslation;
    }

    public function setDescriptionTranslation(?Translation $descriptionTranslation): self
    {
        $this->descriptionTranslation = $descriptionTranslation;

        return $this;
    }

    public function getExitSalary(): ?int
    {
        return $this->exitSalary;
    }

    public function setExitSalary(?int $exitSalary): self
    {
        $this->exitSalary = $exitSalary;

        return $this;
    }

    public function getEmploymentRate(): ?int
    {
        return $this->employmentRate;
    }

    public function setEmploymentRate(?int $employmentRate): self
    {
        $this->employmentRate = $employmentRate;

        return $this;
    }

    public function getCDIRate(): ?int
    {
        return $this->CDIRate;
    }

    public function setCDIRate(?int $CDIRate): self
    {
        $this->CDIRate = $CDIRate;

        return $this;
    }

    public function getAbroadEmploymentRate(): ?int
    {
        return $this->abroadEmploymentRate;
    }

    public function setAbroadEmploymentRate(?int $abroadEmploymentRate): self
    {
        $this->abroadEmploymentRate = $abroadEmploymentRate;

        return $this;
    }

    /**
     * @return Collection|UTTFiliere[]
     */
    public function getUTTFilieres(): Collection
    {
        return $this->filieres;
    }

    public function addUTTFiliere(UTTFiliere $filiere): self
    {
        if (!$this->filieres->contains($filiere)) {
            $this->filieres[] = $filiere;
            $filiere->setUTTBranche($this);
        }

        return $this;
    }

    public function removeUTTFiliere(UTTFiliere $filiere): self
    {
        // set the owning side to null (unless already changed)
        if ($this->filieres->removeElement($filiere) && $filiere->getUTTBranche() === $this) {
            $filiere->setUTTBranche(null);
        }

        return $this;
    }
}
