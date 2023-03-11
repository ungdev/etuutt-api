<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Filter\SoftDeletedFilter;
use App\Repository\UECourseExchangeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * This entity represents a proposition of the author to exchange one of his courses.
 */
#[ApiFilter(SoftDeletedFilter::class)]
#[ORM\Entity(repositoryClass: UECourseExchangeRepository::class)]
#[ORM\Table(name: 'ue_course_exchanges')]
#[ORM\HasLifecycleCallbacks]
class UECourseExchange
{
    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation to the author of this Exchange.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * The relation to the course the author wants to change.
     */
    #[ORM\ManyToOne(targetEntity: UECourse::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?UECourse $courseFrom = null;

    /**
     * The relation to the course the author may want in exchange.
     */
    #[ORM\ManyToOne(targetEntity: UECourse::class)]
    private ?UECourse $courseTo = null;

    /**
     * A boolean to know if this Exchange is still wanted by the author.
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $stillAvailable = true;

    /**
     * The content of the message that goes with the Exchange proposition.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body = null;

    /**
     * The relation to the comments that reply to this exchange proposition.
     *
     * @var Collection<int, UECourseExchangeReply>|UECourseExchangeReply[]
     */
    #[ORM\OneToMany(targetEntity: UECourseExchangeReply::class, mappedBy: 'exchange', orphanRemoval: true)]
    private Collection $responses;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());

        $this->responses = new ArrayCollection();
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCourseFrom(): ?UECourse
    {
        return $this->courseFrom;
    }

    public function setCourseFrom(?UECourse $courseFrom): self
    {
        $this->courseFrom = $courseFrom;

        return $this;
    }

    public function getCourseTo(): ?UECourse
    {
        return $this->courseTo;
    }

    public function setCourseTo(?UECourse $courseTo): self
    {
        $this->courseTo = $courseTo;

        return $this;
    }

    public function getStillAvailable(): bool
    {
        return $this->stillAvailable;
    }

    public function setStillAvailable(bool $stillAvailable): self
    {
        $this->stillAvailable = $stillAvailable;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return Collection|UECourseExchangeReply[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(UECourseExchangeReply $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
            $response->setExchange($this);
        }

        return $this;
    }

    public function removeResponse(UECourseExchangeReply $response): self
    {
        // set the owning side to null (unless already changed)
        if ($this->responses->removeElement($response) && $response->getExchange() === $this) {
            $response->setExchange(null);
        }

        return $this;
    }
}
