<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Repository\UEStarVoteRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that stores the stars given to a UE by a User following a Criterion.
 *
 * @ORM\Entity(repositoryClass=UEStarVoteRepository::class)
 * @ORM\Table(name="ue_stars_votes")
 * @ORM\HasLifecycleCallbacks
 */
class UEStarVote
{
    use TimestampsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Assert\Uuid
     */
    private $id;

    /**
     * The UE that the User rates.
     *
     * @ORM\ManyToOne(targetEntity=UE::class, inversedBy="starVotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UE;

    /**
     * The criterion that is rated.
     *
     * @ORM\ManyToOne(targetEntity=UEStarCriterion::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $criterion;

    /**
     * The User taht rates the UE.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="UEStarVotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * The number of stars of this vote.
     *
     * @ORM\Column(type="smallint")
     *
     * @Assert\Type("int")
     * @Assert\LessThanOrEqual(5)
     * @Assert\GreaterThanOrEqual(0)
     */
    private $value;

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
}
