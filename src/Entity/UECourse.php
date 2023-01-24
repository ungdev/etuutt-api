<?php

namespace App\Entity;

use App\Repository\UECourseRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that represente a Course of a UE.
 *
 * @ORM\Entity(repositoryClass=UECourseRepository::class)
 * @ORM\Table(name="ue_courses")
 */
class UECourse
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Assert\Uuid
     */
    private $id;

    /**
     * The relation to the UE related to this Course.
     *
     * @ORM\ManyToOne(targetEntity=UE::class, inversedBy="courses")
     */
    private $UE;

    /**
     * The day of the week during which this Course takes place.
     *
     * @ORM\Column(type="string", length=20)
     *
     * @Assert\Type("string")
     * @Assert\Choice({"Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"})
     */
    #[Groups([
        'ue:read:one',
    ])]
    private $day;

    /**
     * The starting hour of this Course.
     *
     * @ORM\Column(type="time")
     *
     * @Assert\Time
     */
    #[Groups([
        'ue:read:one',
    ])]
    private $startHour;

    /**
     * The ending hour of this Course.
     *
     * @ORM\Column(type="time")
     *
     * @Assert\Time
     */
    #[Groups([
        'ue:read:one',
    ])]
    private $endHour;

    /**
     * The week code during which the Course takes place.
     *
     * @ORM\Column(type="string", length=1, nullable=true)
     *
     * @Assert\Type("string")
     * @Assert\Choice({"A", "B"})
     */
    #[Groups([
        'ue:read:one',
    ])]
    private $week;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     *
     * @Assert\Type("string")
     * @Assert\Choice({"CM", "TD", "TP"})
     */
    #[Groups([
        'ue:read:one',
    ])]
    private $type;

    /**
     * The place where the Course takes place.
     *
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=50)
     */
    #[Groups([
        'ue:read:one',
    ])]
    private $room;

    /**
     * The relation to the Semester during which the Course takes place.
     *
     * @ORM\ManyToOne(targetEntity=Semester::class)
     * @ORM\JoinColumn(name="semester_code", referencedColumnName="code")
     */
    #[Groups([
        'ue:read:one',
    ])]
    private $semester;

    /**
     * The relation with User to have all students of this Course.
     *
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="courses")
     * @ORM\JoinTable(
     *     name="user_ue_courses",
     *     joinColumns={@ORM\JoinColumn(name="course_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $students;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $createdAt;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());

        $this->students = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUE(): ?UE
    {
        return $this->UE;
    }

    public function setUE(?UE $UE): self
    {
        $this->UE = $UE;

        return $this;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->startHour;
    }

    public function setStartHour(\DateTimeInterface $startHour): self
    {
        $this->startHour = $startHour;

        return $this;
    }

    public function getEndHour(): ?\DateTimeInterface
    {
        return $this->endHour;
    }

    public function setEndHour(\DateTimeInterface $endHour): self
    {
        $this->endHour = $endHour;

        return $this;
    }

    public function getWeek(): ?string
    {
        return $this->week;
    }

    public function setWeek(?string $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRoom(): ?string
    {
        return $this->room;
    }

    public function setRoom(string $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getSemester(): ?Semester
    {
        return $this->semester;
    }

    public function setSemester(?Semester $semester): self
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(User $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
        }

        return $this;
    }

    public function removeStudent(User $student): self
    {
        $this->students->removeElement($student);

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
