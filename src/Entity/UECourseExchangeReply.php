<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\UECourseExchangeReplyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * This entity represents a comment replying to a UECourseExchange.
 */
#[ORM\Entity(repositoryClass: UECourseExchangeReplyRepository::class)]
#[ORM\Table(name: 'ue_course_exchange_replies')]
#[ORM\HasLifecycleCallbacks]
class UECourseExchangeReply
{
    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation to the author of this reply.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * The relation to the exchange this message is replying to.
     */
    #[ORM\ManyToOne(targetEntity: UECourseExchange::class, inversedBy: 'responses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UECourseExchange $exchange = null;

    /**
     * The content of the reply message.
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
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
}
