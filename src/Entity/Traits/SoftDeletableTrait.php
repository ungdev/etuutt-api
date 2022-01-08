<?php

namespace App\Entity\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A trait that entities can use to be soft deletable. It adds a `deletedAt` property with its getter and setter, with a `isSoftDeleted()` method.
 */
trait SoftDeletableTrait
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $deletedAt;

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isSoftDeleted(): bool
    {
        return !(null === $this->deletedAt);
    }
}
