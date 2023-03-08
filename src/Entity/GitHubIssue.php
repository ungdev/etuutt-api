<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\GitHubIssueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that stores the User that has created an issue on GitHub from etu.utt.fr.
 */
#[ORM\Entity(repositoryClass: GitHubIssueRepository::class)]
#[ORM\Table(name: 'github_issues')]
#[ORM\HasLifecycleCallbacks]
class GitHubIssue
{
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation that allow to each User to add own a GitHubIssue.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The id of the GitHub issue.
     */
    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER)]
    private int $gitHubIssueId;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
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

    public function getGitHubIssueId(): int
    {
        return $this->gitHubIssueId;
    }

    public function setGitHubIssueId(int $gitHubIssueId): self
    {
        $this->gitHubIssueId = $gitHubIssueId;

        return $this;
    }
}
