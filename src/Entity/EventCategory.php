<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\EventCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventCategoryRepository::class)
 * @ORM\Table(name="event_categories")
 */
class EventCategory
{
    use UUIDTrait;

    /**
     * The name of the event category.
     *
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $name;

    public function getId()
    {
        return $this->id;
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
}
