<?php

namespace App\Entity;

use App\Repository\UECourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
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
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     *
     * @Assert\Uuid(versions=4)
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
    private $day;

    /**
     * The starting hour of this Course.
     *
     * @ORM\Column(type="time")
     *
     * @Assert\Time
     */
    private $startHour;

    /**
     * The ending hour of this Course.
     *
     * @ORM\Column(type="time")
     *
     * @Assert\Time
     */
    private $endHour;

    /**
     * The week code during which the Course takes place.
     *
     * @ORM\Column(type="string", length=1, nullable=true)
     *
     * @Assert\Type("string")
     * @Assert\Choice({"A", "B"})
     */
    private $week;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     *
     * @Assert\Type("string")
     * @Assert\Choice({"CM", "TD", "TP"})
     */
    private $type;

    /**
     * The place where the Course takes place.
     *
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=50)
     */
    private $room;

    /**
     * The relation to the Semester during which the Course takes place.
     *
     * @ORM\ManyToOne(targetEntity=Semester::class)
     * @ORM\JoinColumn(name="semester_code", referencedColumnName="code")
     */
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
     * @Assert\DateTime
     */
    private $createdAt;

    public function __construct()
    {
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
