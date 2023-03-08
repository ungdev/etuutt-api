<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * A trait that entities can use to have timestamps. It adds a `createdAt` and an `updatedAt` property with their getters and setters.
 */
trait TimestampsTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $updatedAt;

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        //  The first call to `setCreatedAt()` also sets the `updatedAt` property.
        if (!isset($this->createdAt)) {
            $this->updatedAt = $createdAt;
        }

        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Method called by ORM before inserting changes on the entity into the DB. It updates the `updatedAt` property.
     */
    #[ORM\PreUpdate]
    public function updateTimestamp(): self
    {
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }
}
