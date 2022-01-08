<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeletableTrait;
use App\Repository\UECourseExchangeReplyRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This entity represents a comment replying to a UECourseExchange.
 *
 * @ORM\Entity(repositoryClass=UECourseExchangeReplyRepository::class)
 * @ORM\Table(name="ue_course_exchange_replies")
 */
class UECourseExchangeReply
{
    use SoftDeletableTrait;

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
     * The relation to the author of this reply.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * The relation to the exchange this message is replying to.
     *
     * @ORM\ManyToOne(targetEntity=UECourseExchange::class, inversedBy="responses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exchange;

    /**
     * The content of the reply message.
     *
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
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

    public function getExchange(): ?UECourseExchange
    {
        return $this->exchange;
    }

    public function setExchange(?UECourseExchange $exchange): self
    {
        $this->exchange = $exchange;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
