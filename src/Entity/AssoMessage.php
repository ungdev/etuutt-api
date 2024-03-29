<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\AssoMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: AssoMessageRepository::class)]
#[ORM\Table(name: 'asso_messages')]
#[ORM\HasLifecycleCallbacks]
class AssoMessage
{
    use TimestampsTrait;
    use UUIDTrait;

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
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    /**
     * Whether the message should be displayed on mobile or not.
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $sendToMobile = false;

    /**
     * Whether the message should be send in the daymails or not.
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $sendAsDaymail = false;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());

        $this->setTitleTranslation(new Translation());
        $this->setBodyTranslation(new Translation());
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

    public function getSendToMobile(): bool
    {
        return $this->sendToMobile;
    }

    public function setSendToMobile(bool $sendToMobile): self
    {
        $this->sendToMobile = $sendToMobile;

        return $this;
    }

    public function getSendAsDaymail(): bool
    {
        return $this->sendAsDaymail;
    }

    public function setSendAsDaymail(bool $sendAsDaymail): self
    {
        $this->sendAsDaymail = $sendAsDaymail;

        return $this;
    }
}
