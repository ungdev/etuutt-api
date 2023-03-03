<?php

namespace App\Entity;

use DateTimeInterface;
use DateTime;
use App\Repository\UECommentReplyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity of a reply to a Comment on a UE.
 */
#[ORM\Entity(repositoryClass: UECommentReplyRepository::class)]
#[ORM\Table(name: 'ue_comment_replies')]
class UECommentReply
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The relation to the Comment this Reply is for.
     */
    #[ORM\ManyToOne(targetEntity: UEComment::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UEComment $comment = null;

    /**
     * The relation to the User who has created this Reply.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * The content of this Reply.
     */
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $updatedAt;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $deletedAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getComment(): ?UEComment
    {
        return $this->comment;
    }

    public function setComment(?UEComment $comment): self
    {
        $this->comment = $comment;

        return $this;
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
        return null !== $this->deletedAt;
    }
}
