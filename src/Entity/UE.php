<?php

namespace App\Entity;

use App\Repository\UERepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UERepository::class)
 * @ORM\Table(name="ues")
 */
class UE
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Assert\Uuid()
     */
    private $id;

    /**
     * The code of the UE (e.g. "MATH01").
     *
     * @ORM\Column(type="string", length=10)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=10)
     * @Assert\Regex("/^[a-zA-Z]{1,5}[0-9]{1,2}$/")
     */
    private $code;

    /**
     * The title of the UE (e.g. "Analyse : suites et fonctions d’une variable réelle pour les TC01 ou les TC05 aguerris.").
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=255)
     */
    private $name;

    /**
     * The validation rate computed with data in our database.
     *
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type("float")
     * @Assert\LessThanOrEqual(100)
     * @Assert\GreaterThanOrEqual(0)
     */
    private $validationRate;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $updatedAt;

    /**
     * The list of subscriptions to this UE by Users.
     *
     * @ORM\OneToMany(targetEntity=UserUESubscription::class, mappedBy="UE", orphanRemoval=true)
     */
    private $usersSubscriptions;

    /**
     * The potential UTTFiliere of which this UE belongs to. It is optional.
     *
     * @ORM\ManyToOne(targetEntity=UTTFiliere::class, inversedBy="UEs")
     * @ORM\JoinColumn(name="filiere_code", referencedColumnName="code")
     */
    private $filiere;

    /**
     * The amount of UECredits of this UE. A UECredit object is a number of credit in a UECreditCategory.
     *
     * @ORM\OneToMany(targetEntity=UECredit::class, mappedBy="UE", orphanRemoval=true)
     */
    private $credits;

    /**
     * All UEStarVote related to this UE.
     *
     * @ORM\OneToMany(targetEntity=UEStarVote::class, mappedBy="UE", orphanRemoval=true)
     */
    private $starVotes;

    /**
     * The relation that allow to know that many UEs can be open during many Semesters.
     *
     * @ORM\ManyToMany(targetEntity=Semester::class)
     * @ORM\JoinTable(
     *     name="ue_open_semesters",
     *     joinColumns={@ORM\JoinColumn(name="ue_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="semester_code", referencedColumnName="code")}
     * )
     */
    private $openSemester;

    /**
     * The relation to the entity that store the work time of this UE.
     *
     * @ORM\OneToOne(targetEntity=UEWorkTime::class, mappedBy="UE", cascade={"persist", "remove"})
     */
    private $workTime;

    /**
     * The relation to the entity that store the info of this UE given by UTT.
     *
     * @ORM\OneToOne(targetEntity=UEInfo::class, mappedBy="UE", cascade={"persist", "remove"})
     */
    private $info;

    /**
     * The relation to all UEAnnals related to this UE.
     *
     * @ORM\OneToMany(targetEntity=UEAnnal::class, mappedBy="UE", orphanRemoval=true)
     */
    private $annals;

    /**
     * The relation to all UEComments related to this UE.
     *
     * @ORM\OneToMany(targetEntity=UEComment::class, mappedBy="UE", orphanRemoval=true)
     */
    private $comments;

    /**
     * The relation to all UECourses of this UE.
     *
     * @ORM\OneToMany(targetEntity=UECourse::class, mappedBy="UE")
     */
    private $courses;

    public function __construct()
    {
        $this->usersSubscriptions = new ArrayCollection();
        $this->credits = new ArrayCollection();
        $this->starVotes = new ArrayCollection();
        $this->openSemester = new ArrayCollection();
        $this->annals = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValidationRate(): ?float
    {
        return $this->validationRate;
    }

    public function setValidationRate(?float $validationRate): self
    {
        $this->validationRate = $validationRate;

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
     * @return Collection|UserUESubscription[]
     */
    public function getUserUESubscriptions(): Collection
    {
        return $this->usersSubscriptions;
    }

    public function addUserUESubscriptions(UserUESubscription $userUESubscription): self
    {
        if (!$this->UserUESubscriptions->contains($userUESubscription)) {
            $this->UserUESubscriptions[] = $userUESubscription;
            $userUESubscription->setUE($this);
        }

        return $this;
    }

    public function removeUserUESubscriptions(UserUESubscription $userUESubscription): self
    {
        if ($this->UserUESubscriptions->removeElement($userUESubscription)) {
            // set the owning side to null (unless already changed)
            if ($userUESubscription->getUE() === $this) {
                $userUESubscription->setUE(null);
            }
        }

        return $this;
    }

    public function getFiliere(): ?UTTFiliere
    {
        return $this->filiere;
    }

    public function setFiliere(?UTTFiliere $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * @return Collection|UECredit[]
     */
    public function getCredits(): Collection
    {
        return $this->credits;
    }

    public function addCredit(UECredit $credit): self
    {
        if (!$this->credits->contains($credit)) {
            $this->credits[] = $credit;
            $credit->setUE($this);
        }

        return $this;
    }

    public function removeCredit(UECredit $credit): self
    {
        if ($this->credits->removeElement($credit)) {
            // set the owning side to null (unless already changed)
            if ($credit->getUE() === $this) {
                $credit->setUE(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UEStarVote[]
     */
    public function getStarVotes(): Collection
    {
        return $this->starVotes;
    }

    public function addStarVote(UEStarVote $starVote): self
    {
        if (!$this->starVotes->contains($starVote)) {
            $this->starVotes[] = $starVote;
            $starVote->setUE($this);
        }

        return $this;
    }

    public function removeStarVote(UEStarVote $starVote): self
    {
        if ($this->starVotes->removeElement($starVote)) {
            // set the owning side to null (unless already changed)
            if ($starVote->getUE() === $this) {
                $starVote->setUE(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Semester[]
     */
    public function getOpenSemester(): Collection
    {
        return $this->openSemester;
    }

    public function addOpenSemester(Semester $openSemester): self
    {
        if (!$this->openSemester->contains($openSemester)) {
            $this->openSemester[] = $openSemester;
        }

        return $this;
    }

    public function removeOpenSemester(Semester $openSemester): self
    {
        $this->openSemester->removeElement($openSemester);

        return $this;
    }

    public function getWorkTime(): ?UEWorkTime
    {
        return $this->workTime;
    }

    public function setWorkTime(UEWorkTime $workTime): self
    {
        // set the owning side of the relation if necessary
        if ($workTime->getUE() !== $this) {
            $workTime->setUE($this);
        }

        $this->workTime = $workTime;

        return $this;
    }

    public function getInfo(): ?UEInfo
    {
        return $this->info;
    }

    public function setInfo(UEInfo $info): self
    {
        // set the owning side of the relation if necessary
        if ($info->getUE() !== $this) {
            $info->setUE($this);
        }

        $this->info = $info;

        return $this;
    }

    /**
     * @return Collection|UEAnnal[]
     */
    public function getAnnals(): Collection
    {
        return $this->annals;
    }

    public function addAnnal(UEAnnal $annal): self
    {
        if (!$this->annals->contains($annal)) {
            $this->annals[] = $annal;
            $annal->setUE($this);
        }

        return $this;
    }

    public function removeAnnal(UEAnnal $annal): self
    {
        if ($this->annals->removeElement($annal)) {
            // set the owning side to null (unless already changed)
            if ($annal->getUE() === $this) {
                $annal->setUE(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UEComment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(UEComment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUE($this);
        }

        return $this;
    }

    public function removeComment(UEComment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUE() === $this) {
                $comment->setUE(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UECourse[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(UECourse $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setUE($this);
        }

        return $this;
    }

    public function removeCourse(UECourse $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getUE() === $this) {
                $course->setUE(null);
            }
        }

        return $this;
    }
}
