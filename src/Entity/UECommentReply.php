<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\UECommentReplyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * The entity of a reply to a Comment on a UE.
 */
#[ORM\Entity(repositoryClass: UECommentReplyRepository::class)]
#[ORM\Table(name: 'ue_comment_replies')]
#[ORM\HasLifecycleCallbacks]
class UECommentReply
{
    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;

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
    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
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
}
