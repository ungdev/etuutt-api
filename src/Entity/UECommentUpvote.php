<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\UECommentUpvoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * This entity is a vote of a User to a Comment to bring it to the fore.
 *
 * @ORM\Entity(repositoryClass=UECommentUpvoteRepository::class)
 * @ORM\Table(
 *     name="ue_comment_upvotes",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="assignment_unique", columns={"comment_id", "user_id"})
 *     }
 * )
 * Explanation uniqueConstraints :
 * A User's vote for a Comment is unique.
 * @ORM\HasLifecycleCallbacks
 */
class UECommentUpvote
{
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation to the Comment that this Upvote is for.
     *
     * @ORM\ManyToOne(targetEntity=UEComment::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $comment;

    /**
     * The relation to the User that this Upvote is from.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
