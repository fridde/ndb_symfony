<?php

namespace App\Entity;

use App\Repository\Filterable;
use App\Repository\VisitRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: VisitRepository::class), ORM\Table(name: "visits")]
class Visit implements \JsonSerializable
{
    use DefaultSerializable;

    public array $standard_members = ['id', 'Time', 'Status', 'BusIsBooked', 'FoodIsBooked'];

    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected int $id;

    #[ORM\ManyToOne(inversedBy: "Visits")]
    protected ?Group $Group;

    #[ORM\Column]
    protected \DateTime $Date;

    #[ORM\ManyToOne(inversedBy: "Visits")]
    protected Topic $Topic;

    /** This is the owning side. The visit has many colleagues (=users)
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: "Visits")]
    #[ORM\JoinTable(name: "colleagues_visits")]
    protected Collection $Colleagues;

    #[ORM\OneToMany(mappedBy: "Visit", targetEntity: Note::class)]
    protected Collection $Notes;

    #[ORM\Column]
    protected bool $Confirmed = false;

    #[ORM\Column(nullable: true)]
    protected ?string $Time;

    #[ORM\Column]
    protected bool $Status = true;

    #[ORM\Column]
    protected bool $BusIsBooked = false;

    #[ORM\Column]
    protected bool $FoodIsBooked = false;



    public function __construct()
    {
        $this->Notes = new ArrayCollection();
    }

    public function __toString(): string
    {
        $s = $this->getDateString() . ', ';
        $s .= $this->getTopic()->getShortName();
        if ($this->hasGroup()) {
            $s .= ', ' . strtoupper($this->getGroup()->getSchool()->getId());
            $s .= ': ' . $this->getGroup()->getName();
        }
        return $s;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getGroup(): ?Group
    {
        return $this->Group;
    }


    public function setGroup(?Group $Group): void
    {
        $this->Group = $Group;
    }

    public function hasGroup(): bool
    {
        return $this->getGroup() !== null;
    }


    public function getDate(): \DateTime
    {
        return $this->Date;
    }


    public function setDate(\DateTime $Date): void
    {
        $this->Date = $Date;
    }

    public function getDateString(): string
    {
        return Carbon::instance($this->getDate())->toDateString();
    }


    public function getTopic(): Topic
    {
        return $this->Topic;
    }


    public function setTopic(Topic $Topic): void
    {
        $this->Topic = $Topic;
    }


    public function getColleagues(): Collection
    {
        return $this->Colleagues;
    }


    public function setColleagues(Collection $Colleagues): void
    {
        $this->Colleagues = $Colleagues;
    }


    public function getNotes(): Collection
    {
        return $this->Notes;
    }


    public function setNotes(Collection $Notes): void
    {
        $this->Notes = $Notes;
    }


    public function isConfirmed(): bool
    {
        return $this->Confirmed;
    }


    public function setConfirmed(bool $Confirmed): void
    {
        $this->Confirmed = $Confirmed;
    }


    public function getTime(): ?string
    {
        return $this->Time;
    }


    public function setTime(?string $Time): void
    {
        $this->Time = $Time;
    }


    public function isStatus(): bool
    {
        return $this->Status;
    }


    public function setStatus(bool $Status): void
    {
        $this->Status = $Status;
    }

    public function isBusIsBooked(): bool
    {
        return $this->BusIsBooked;
    }


    public function setBusIsBooked(bool $BusIsBooked): void
    {
        $this->BusIsBooked = $BusIsBooked;
    }


    public function isFoodIsBooked(): bool
    {
        return $this->FoodIsBooked;
    }


    public function setFoodIsBooked(bool $FoodIsBooked): void
    {
        $this->FoodIsBooked = $FoodIsBooked;
    }


    public function jsonSerialize()
    {
        return $this->getStandardMembers();
    }
}