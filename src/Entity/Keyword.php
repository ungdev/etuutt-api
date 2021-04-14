<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\KeywordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=KeywordRepository::class)
 * @ORM\Table(name="keywords")
 */
class Keyword
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=30, unique=true)
     *
     * @Assert\Regex("/^[a-z]{1,30}$/")
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Asso::class, mappedBy="keywords")
     * @ORM\JoinTable(name="asso_keywords")
     */
    private $assos;

    public function __construct()
    {
        $this->assos = new ArrayCollection();
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

    /**
     * @return Collection|Asso[]
     */
    public function getAssos(): Collection
    {
        return $this->assos;
    }

    public function addAsso(Asso $asso): self
    {
        if (!$this->assos->contains($asso)) {
            $this->assos[] = $asso;
            $asso->addKeyword($this);
        }

        return $this;
    }

    public function removeAsso(Asso $asso): self
    {
        if ($this->assos->removeElement($asso)) {
            $asso->removeKeyword($this);
        }

        return $this;
    }
}
