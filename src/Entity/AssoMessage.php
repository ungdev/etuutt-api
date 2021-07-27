<?php

namespace App\Entity;

use App\Repository\AssoMessageRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AssoMessageRepository::class)
 * @ORM\Table(name="asso_messages")
 */
class AssoMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     *
     * @Assert\Uuid(versions={4})
     */
    private $id;

    /**
     * The relation to the Asso that sent this AssoMessage.
     *
     * @ORM\ManyToOne(targetEntity=Asso::class, inversedBy="assoMessages")
     * @ORM\JoinColumn(name="asso_id", nullable=false)
     */
    private $asso;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=60)
     */
    private $title;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class)
     * @ORM\JoinColumn(name="body_traduction_code", referencedColumnName="code", nullable=false)
     */
    private $bodyTranslation;

    /**
     * The date of the event presented in the message.
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $date;

    /**
     * Whether the message should be displayed on mobile or not.
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type("bool")
     */
    private $sendToMobile;

    /**
     * Whether the message should be send in the daymails or not.
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type("bool")
     */
    private $sendAsDaymail;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $createdAt;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getAsso(): ?Asso
    {
        return $this->asso;
    }

    public function setAsso(?Asso $asso): self
    {
        $this->asso = $asso;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBodyTranslation(): ?Translation
    {
        return $this->bodyTranslation;
    }

    public function setBodyTranslation(?Translation $bodyTranslation): self
    {
        $this->bodyTranslation = $bodyTranslation;

        return $this;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSendToMobile(): ?bool
    {
        return $this->sendToMobile;
    }

    public function setSendToMobile(bool $sendToMobile): self
    {
        $this->sendToMobile = $sendToMobile;

        return $this;
    }

    public function getSendAsDaymail(): ?bool
    {
        return $this->sendAsDaymail;
    }

    public function setSendAsDaymail(bool $sendAsDaymail): self
    {
        $this->sendAsDaymail = $sendAsDaymail;

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
}
