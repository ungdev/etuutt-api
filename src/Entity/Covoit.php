<?php

namespace App\Entity;

use DateTimeInterface;
use DateTime;
use App\Repository\CovoitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CovoitRepository::class)]
#[ORM\Table(name: 'covoits')]
class Covoit
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The description of this Covoit. It is optional.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * The maximum number of passengers of this Covoit.
     */
    #[Assert\Type('int')]
    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $capacity = null;

    /**
     * The price in cents (x100).
     */
    #[Assert\Type('int')]
    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $price = null;

    /**
     * The URL of this Covoit on the blablacar website. It is optional.
     */
    #[Assert\Url]
    #[ORM\Column(type: Types::STRING, length: 255, unique: true, nullable: true)]
    private ?string $blablacarUrl = null;

    /**
     * The starting address of the Covoit.
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $startAddress = null;

    /**
     * The end address (destination) of the Covoit.
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $endAddress = null;

    /**
     * The ID of the start city based on this website : https://geoservices.ign.fr/services-web-essentiels.
     */
    #[Assert\Uuid]
    #[ORM\Column(type: 'uuid', nullable: true)]
    private Uuid $startCityId;

    /**
     * The ID of the end city based on this website : https://geoservices.ign.fr/services-web-essentiels.
     */
    #[Assert\Uuid]
    #[ORM\Column(type: 'uuid', nullable: true)]
    private $endCityId;

    /**
     * The starting date of the Covoit.
     */
    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $startAt;

    /**
     * The end date of the Covoit.
     */
    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $endAt = null;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $updatedAt;

    /**
     * The relation between the Covoit and its CovoitMessages.
     *
     * @var Collection<int, CovoitMessage>|CovoitMessage[]
     */
    #[ORM\OneToMany(targetEntity: CovoitMessage::class, mappedBy: 'covoit', orphanRemoval: true)]
    private Collection $covoitMessages;

    /**
     * The relation between the Covoit and the User that created it.
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'createdCovoits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * The relation between the Covoit and the User that are subscribed to it.
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'passengerCovoits')]
    #[ORM\JoinTable(name: 'covoits_users_passengers', inverseJoinColumns: [new ORM\JoinColumn(name: 'covoit_id', referencedColumnName: 'id')], joinColumns: [new ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')])]
    private Collection $passengers;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());

        $this->covoitMessages = new ArrayCollection();
        $this->passengers = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBlablacarUrl(): ?string
    {
        return $this->blablacarUrl;
    }

    public function setBlablacarUrl(string $blablacarUrl): self
    {
        $this->blablacarUrl = $blablacarUrl;

        return $this;
    }

    public function getStartAddress(): ?string
    {
        return $this->startAddress;
    }

    public function setStartAddress(string $startAddress): self
    {
        $this->startAddress = $startAddress;

        return $this;
    }

    public function getEndAddress(): ?string
    {
        return $this->endAddress;
    }

    public function setEndAddress(string $endAddress): self
    {
        $this->endAddress = $endAddress;

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

    /**
     * @return Collection|CovoitMessage[]
     */
    public function getCovoitMessages(): Collection
    {
        return $this->covoitMessages;
    }

    public function addCovoitMessage(CovoitMessage $covoitMessage): self
    {
        if (!$this->covoitMessages->contains($covoitMessage)) {
            $this->covoitMessages[] = $covoitMessage;
            $covoitMessage->setCovoit($this);
        }

        return $this;
    }

    public function removeCovoitMessage(CovoitMessage $covoitMessage): self
    {
        // set the owning side to null (unless already changed)
        if ($this->covoitMessages->removeElement($covoitMessage) && $covoitMessage->getCovoit() === $this) {
            $covoitMessage->setCovoit(null);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getPassengers(): Collection
    {
        return $this->passengers;
    }

    public function addPassenger(User $user): self
    {
        if (!$this->passengers->contains($user)) {
            $this->passengers[] = $user;
        }

        return $this;
    }

    public function removePassenger(User $user): self
    {
        $this->passengers->removeElement($user);

        return $this;
    }

    /**
     * @return bool - Return a boolean to indicate if the number of users linked to the covoit is equal or superior to the capacity
     */
    public function isFull(): bool
    {
        return $this->getPassengers()->count() >= $this->getCapacity();
    }

    public function getStartCityId(): Uuid
    {
        return $this->startCityId;
    }

    public function setStartCityId(Uuid $startCityId): self
    {
        $this->startCityId = $startCityId;

        return $this;
    }

    public function getStartAt(): ?DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getEndCityId()
    {
        return $this->endCityId;
    }

    public function setEndCityId($endCityId): self
    {
        $this->endCityId = $endCityId;

        return $this;
    }
}
