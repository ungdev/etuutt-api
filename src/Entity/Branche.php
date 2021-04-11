<?php

namespace App\Entity;

use App\Entity\Traduction;
use App\Entity\Filiere;
use App\Repository\BrancheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BrancheRepository::class)
 * @ORM\Table(name="branches")
 */
class Branche
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10)
     * 
     * @Assert\Regex("/^[A-Z\d]{1,10}$/")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Traduction::class)
     * @ORM\JoinColumn(name="description_traduction_code", referencedColumnName="code")
     */
    private $descriptionTraduction;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $exitSalary;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $employmentRate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $CDIRate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $abroadEmploymentRate;

    /**
     * @ORM\OneToMany(targetEntity=Filiere::class, mappedBy="branche", orphanRemoval=true)
     */
    private $filieres;

    public function __construct(String $code = null)
    {
        $this->code = $code;
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

    public function getDescriptionTraduction(): ?Traduction
    {
        return $this->descriptionTraduction;
    }

    public function setDescriptionTraduction(?Traduction $descriptionTraduction): self
    {
        $this->descriptionTraduction = $descriptionTraduction;

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
     * @return Collection|Filiere[]
     */
    public function getFilieres(): Collection
    {
        return $this->filieres;
    }

    public function addFiliere(Filiere $filiere): self
    {
        if (!$this->filieres->contains($filiere)) {
            $this->filieres[] = $filiere;
            $filiere->setBranche($this);
        }

        return $this;
    }

    public function removeFiliere(Filiere $filiere): self
    {
        if ($this->filieres->removeElement($filiere)) {
            // set the owning side to null (unless already changed)
            if ($filiere->getBranche() === $this) {
                $filiere->setBranche(null);
            }
        }

        return $this;
    }
}
