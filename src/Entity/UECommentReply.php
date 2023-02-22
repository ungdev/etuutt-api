<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\UECommentReplyRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity of a reply to a Comment on a UE.
 *
 * @ORM\Entity(repositoryClass=UECommentReplyRepository::class)
 * @ORM\Table(name="ue_comment_replies")
 * @ORM\HasLifecycleCallbacks
 */
class UECommentReply
{
    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation to the Comment this Reply is for.
     *
     * @ORM\ManyToOne(targetEntity=UEComment::class, inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comment;

    /**
     * The relation to the User who has created this Reply.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * The content of this Reply.
     *
     * @ORM\Column(type="text")
     * @Assert\Type("string")
     */
    private $body;

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
