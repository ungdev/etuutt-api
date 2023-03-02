<?php

namespace App\Entity;

use App\Repository\UECourseExchangeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This entity represents a proposition of the author to exchange one of his courses.
 *
 * @ORM\Entity(repositoryClass=UECourseExchangeRepository::class)
 * @ORM\Table(name="ue_course_exchanges")
 */
class UECourseExchange
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    #[Assert\Uuid]
    private ?Uuid $id = null;

    /**
     * The relation to the author of this Exchange.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $author = null;

    /**
     * The relation to the course the author wants to change.
     *
     * @ORM\ManyToOne(targetEntity=UECourse::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?UECourse $courseFrom = null;

    /**
     * The relation to the course the author may want in exchange.
     *
     * @ORM\ManyToOne(targetEntity=UECourse::class)
     */
    private ?UECourse $courseTo = null;

    /**
     * A boolean to know if this Exchange is still wanted by the author.
     *
     * @ORM\Column(type="boolean")
     */
    #[Assert\Type('bool')]
    private ?bool $stillAvailable = null;

    /**
     * The content of the message that goes with the Exchange proposition.
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $body = null;

    /**
     * The relation to the comments that reply to this exchange proposition.
     *
     * @ORM\OneToMany(targetEntity=UECourseExchangeReply::class, mappedBy="exchange", orphanRemoval=true)
     */
    private Collection $responses;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Assert\Type('\DateTimeInterface')]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Assert\Type('\DateTimeInterface')]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Assert\Type('\DateTimeInterface')]
    private ?\DateTimeInterface $deletedAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());

        $this->responses = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getStillAvailable(): ?bool
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isSoftDeleted(): bool
    {
        return null !== $this->deletedAt;
    }
}
