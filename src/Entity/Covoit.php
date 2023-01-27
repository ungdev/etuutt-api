<?php

namespace App\Entity;

use App\Repository\CovoitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CovoitRepository::class)
 * @ORM\Table(name="covoits")
 */
class Covoit
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @Assert\Uuid
     */
    private $id;

    /**
     * The description of this Covoit. It is optional.
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * The maximum number of passengers of this Covoit.
     *
     * @ORM\Column(type="smallint")
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private $capacity;

    /**
     * The price in cents (x100).
     *
     * @ORM\Column(type="integer")
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private $price;

    /**
     * The URL of this Covoit on the blablacar website. It is optional.
     *
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     * @Assert\Url
     */
    private $blablacarUrl;

    /**
     * The starting address of the Covoit.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $startAddress;

    /**
     * The end address (destination) of the Covoit.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $endAddress;

    /**
     * The ID of the start city based on this website : https://geoservices.ign.fr/services-web-essentiels.
     *
     * @ORM\Column(type="uuid", nullable=true)
     * @Assert\Uuid
     */
    private $startCityId;

    /**
     * The ID of the end city based on this website : https://geoservices.ign.fr/services-web-essentiels.
     *
     * @ORM\Column(type="uuid", nullable=true)
     * @Assert\Uuid
     */
    private $endCityId;

    /**
     * The starting date of the Covoit.
     *
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private $startAt;

    /**
     * The end date of the Covoit.
     *
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private $endAt;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private $updatedAt;

    /**
     * The relation between the Covoit and its CovoitMessages.
     *
     * @ORM\OneToMany(targetEntity=CovoitMessage::class, mappedBy="covoit", orphanRemoval=true)
     */
    private $covoitMessages;

    /**
     * The relation between the Covoit and the User that created it.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdCovoits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * The relation between the Covoit and the User that are subscribed to it.
     *
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="passengerCovoits")
     * @ORM\JoinTable(
     *     name="covoits_users_passengers",
     *     inverseJoinColumns={@ORM\JoinColumn(name="covoit_id", referencedColumnName="id")},
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * )
     */
    private $passengers;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
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
        if ($this->covoitMessages->removeElement($covoitMessage)) {
            // set the owning side to null (unless already changed)
            if ($covoitMessage->getCovoit() === $this) {
                $covoitMessage->setCovoit(null);
            }
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

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
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
