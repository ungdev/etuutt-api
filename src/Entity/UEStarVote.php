<?php

namespace App\Entity;

use DateTimeInterface;
use DateTime;
use App\Repository\UEStarVoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that stores the stars given to a UE by a User following a Criterion.
 */
#[ORM\Entity(repositoryClass: UEStarVoteRepository::class)]
#[ORM\Table(name: 'ue_stars_votes')]
class UEStarVote
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The UE that the User rates.
     */
    #[ORM\ManyToOne(targetEntity: UE::class, inversedBy: 'starVotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $UE = null;

    /**
     * The criterion that is rated.
     */
    #[ORM\ManyToOne(targetEntity: UEStarCriterion::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?UEStarCriterion $criterion = null;

    /**
     * The User taht rates the UE.
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'UEStarVotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The number of stars of this vote.
     */
    #[Assert\Type('int')]
    #[Assert\LessThanOrEqual(5)]
    #[Assert\GreaterThanOrEqual(0)]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $value = null;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUE(): ?UE
    {
        return $this->UE;
    }

    public function setUE(?UE $UE): self
    {
        $this->UE = $UE;

        return $this;
    }

    public function getCriterion(): ?UEStarCriterion
    {
        return $this->criterion;
    }

    public function setCriterion(?UEStarCriterion $criterion): self
    {
        $this->criterion = $criterion;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
