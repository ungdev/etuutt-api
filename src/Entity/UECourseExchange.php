<?php

namespace App\Entity;

use App\Repository\UECourseExchangeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UECourseExchangeRepository::class)
 * @ORM\Table(name="ue_course_exchanges")
 */
class UECourseExchange
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     *
     * @Assert\Uuid(versions=4)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=UECourse::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $courseFrom;

    /**
     * @ORM\ManyToOne(targetEntity=UECourse::class)
     */
    private $courseTo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stillAvailable;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Assert\DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Assert\DateTime
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity=UECourseExchangeResponse::class, mappedBy="exchange", orphanRemoval=true)
     */
    private $responses;

    public function __construct()
    {
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

    /**
     * @return Collection|UECourseExchangeResponse[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(UECourseExchangeResponse $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
            $response->setExchange($this);
        }

        return $this;
    }

    public function removeResponse(UECourseExchangeResponse $response): self
    {
        if ($this->responses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getExchange() === $this) {
                $response->setExchange(null);
            }
        }

        return $this;
    }
}
