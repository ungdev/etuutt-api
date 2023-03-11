<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Filter\SoftDeletedFilter;
use App\Repository\EventAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiFilter(SoftDeletedFilter::class)]
#[ORM\Entity(repositoryClass: EventAnswerRepository::class)]
#[ORM\Table(name: 'event_answers')]
#[ORM\HasLifecycleCallbacks]
class EventAnswer
{
    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation between the EventAnswer and its Event.
     */
    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'eventAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    /**
     * The relation to the User that wrote the EventAnswer.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The answer of the User to the Event (e.g. "je viens").
     */
    #[Assert\Length(min: 1, max: 20)]
    #[ORM\Column(type: Types::STRING, length: 20)]
    private ?string $answer = null;

    /**
     * The comment of the User concerning the Event. It is optional.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

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

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
