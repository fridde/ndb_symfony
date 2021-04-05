<?php

namespace App\Entity;

use App\Repository\Filterable;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VisitRepository")
 * @ORM\Table(name="visits")
 */
class Visit implements \JsonSerializable
{
    use DefaultSerializable;

    public array $standard_members = ['id', 'Time', 'Status', 'BusIsBooked', 'FoodIsBooked'];

    /** @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /** @ORM\ManyToOne(targetEntity="Group", inversedBy="Visits")
     */
    protected ?Group $Group;

    /** @ORM\Column(type="datetime")
     */
    protected \DateTime $Date;

    /** @ORM\ManyToOne(targetEntity="Topic", inversedBy="Visits")
     */
    protected Topic $Topic;

    /** This is the owning side. The visit has many colleagues (=users)
     * @ORM\ManyToMany(targetEntity="User", inversedBy="Visits")
     * @ORM\JoinTable(name="colleagues_visits")
     */
    protected Collection $Colleagues;

    /** @ORM\OneToMany(targetEntity="Note", mappedBy="Visit") */
    protected Collection $Notes;

    /** @ORM\Column(type="boolean")
     */
    protected bool $Confirmed = false;

    /** @ORM\Column(type="string", nullable=true)
     */
    protected ?string $Time;

    /** @ORM\Column(type="boolean")
     */
    protected bool $Status = true;

    /** @ORM\Column(type="boolean")
     */
    protected bool $BusIsBooked = false;

    /** @ORM\Column(type="boolean")
     */
    protected bool $FoodIsBooked = false;

    /**
     * Visit constructor.
     */
    public function __construct()
    {
        $this->Notes = new ArrayCollection();
    }

    public function __toString(): string
    {
        $s = $this->getDateString() . ', ';
        $s .= $this->getTopic()->getShortName();
        if($this->hasGroup()){
            $s .= ', ' . strtoupper($this->getGroup()->getSchool()->getId());
            $s .= ': ' . $this->getGroup()->getName();
        }
        return $s;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Group|null
     */
    public function getGroup(): ?Group
    {
        return $this->Group;
    }

    /**
     * @param Group|null $Group
     */
    public function setGroup(?Group $Group): void
    {
        $this->Group = $Group;
    }

    public function hasGroup(): bool
    {
        return $this->getGroup() !== null;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->Date;
    }

    /**
     * @param \DateTime $Date
     */
    public function setDate(\DateTime $Date): void
    {
        $this->Date = $Date;
    }

    public function getDateString(): string
    {
        return Carbon::instance($this->getDate())->toDateString();
    }

    /**
     * @return Topic
     */
    public function getTopic(): Topic
    {
        return $this->Topic;
    }

    /**
     * @param Topic $Topic
     */
    public function setTopic(Topic $Topic): void
    {
        $this->Topic = $Topic;
    }

    /**
     * @return Collection
     */
    public function getColleagues(): Collection
    {
        return $this->Colleagues;
    }

    /**
     * @param Collection $Colleagues
     */
    public function setColleagues(Collection $Colleagues): void
    {
        $this->Colleagues = $Colleagues;
    }

    /**
     * @return Collection
     */
    public function getNotes(): Collection
    {
        return $this->Notes;
    }

    /**
     * @param Collection $Notes
     */
    public function setNotes(Collection $Notes): void
    {
        $this->Notes = $Notes;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->Confirmed;
    }

    /**
     * @param bool $Confirmed
     */
    public function setConfirmed(bool $Confirmed): void
    {
        $this->Confirmed = $Confirmed;
    }

    /**
     * @return string|null
     */
    public function getTime(): ?string
    {
        return $this->Time;
    }

    /**
     * @param string|null $Time
     */
    public function setTime(?string $Time): void
    {
        $this->Time = $Time;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->Status;
    }

    /**
     * @param bool $Status
     */
    public function setStatus(bool $Status): void
    {
        $this->Status = $Status;
    }

    /**
     * @return bool
     */
    public function isBusIsBooked(): bool
    {
        return $this->BusIsBooked;
    }

    /**
     * @param bool $BusIsBooked
     */
    public function setBusIsBooked(bool $BusIsBooked): void
    {
        $this->BusIsBooked = $BusIsBooked;
    }

    /**
     * @return bool
     */
    public function isFoodIsBooked(): bool
    {
        return $this->FoodIsBooked;
    }

    /**
     * @param bool $FoodIsBooked
     */
    public function setFoodIsBooked(bool $FoodIsBooked): void
    {
        $this->FoodIsBooked = $FoodIsBooked;
    }


    public function jsonSerialize()
    {
        $return = $this->getStandardMembers();

        return $return;
    }
}