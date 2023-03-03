<?php

namespace App\Entity;

use App\Repository\AssoMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssoMessageRepository::class)]
#[ORM\Table(name: 'asso_messages')]
class AssoMessage
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The relation to the Asso that sent this AssoMessage.
     */
    #[ORM\ManyToOne(targetEntity: Asso::class, inversedBy: 'assoMessages')]
    #[ORM\JoinColumn(name: 'asso_id', nullable: false)]
    private ?Asso $asso = null;

    /**
     * The Translation object that contains the translation of the title.
     */
    #[SerializedName('title')]
    #[ORM\ManyToOne(targetEntity: Translation::class, cascade: ['persist', 'remove'])]
    private ?Translation $titleTranslation = null;

    /**
     * The Translation object that contains the translation of the description.
     */
    #[SerializedName('body')]
    #[ORM\ManyToOne(targetEntity: Translation::class, cascade: ['persist', 'remove'])]
    private ?Translation $bodyTranslation = null;

    /**
     * The date of the event presented in the message.
     */
    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    /**
     * Whether the message should be displayed on mobile or not.
     */
    #[Assert\Type('bool')]
    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $sendToMobile = null;

    /**
     * Whether the message should be send in the daymails or not.
     */
    #[Assert\Type('bool')]
    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $sendAsDaymail = null;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());

        $this->setTitleTranslation(new Translation());
        $this->setBodyTranslation(new Translation());
    }

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

    public function getTitleTranslation(): ?Translation
    {
        return $this->titleTranslation;
    }

    public function setTitleTranslation(?Translation $titleTranslation): self
    {
        $this->titleTranslation = $titleTranslation;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
