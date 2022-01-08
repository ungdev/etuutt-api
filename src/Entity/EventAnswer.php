<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\EventAnswerRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventAnswerRepository::class)
 * @ORM\Table(name="event_answers")
 * @ORM\HasLifecycleCallbacks
 */
class EventAnswer
{
    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation between the EventAnswer and its Event.
     *
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="eventAnswers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * The relation to the User that wrote the EventAnswer.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * The answer of the User to the Event (e.g. "je viens").
     *
     * @ORM\Column(type="string", length=20)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=20)
     */
    private $answer;

    /**
     * The comment of the User concerning the Event. It is optional.
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
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
