<?php

namespace App\Entity;

use App\Repository\AssoKeywordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AssoKeywordRepository::class)
 * @ORM\Table(name="keywords")
 */
class AssoKeyword
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
     */
    private $assos;

    public function __construct($name)
    {
        $this->name = $name;
        $this->assos = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Asso[]|Collection
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
