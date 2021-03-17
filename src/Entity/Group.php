<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @ORM\Table(name="groups")
 */
class Group
{
    /** @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /** @ORM\Column(type="string", nullable=true)
     */
    protected ?string $Name;

    /** @ORM\ManyToOne(targetEntity="User", inversedBy="Groups")
     */
    protected User $User;

    /** @ORM\ManyToOne(targetEntity="School", inversedBy="Groups")     * */
    protected School $School;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Segment;

    /** @ORM\Column(type="smallint", nullable=true) */
    protected ?int $StartYear;

    /** @ORM\Column(type="smallint", nullable=true)
     */
    protected ?int $NumberStudents;

    /** @ORM\Column(type="text", nullable=true)
     */
    protected ?string $Food;

    /** @ORM\Column(type="text", nullable=true)
     */
    protected ?string $Info;

    /** @ORM\Column(type="smallint") */
    protected int $Status = self::ACTIVE;

    /** @ORM\OneToMany(targetEntity="Visit", mappedBy="Group") */
    protected Collection $Visits;

    public const ARCHIVED = 0;
    public const ACTIVE = 1;

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->Visits = new ArrayCollection();
    }

    public function __toString()
    {
        $s = '[' . strtoupper($this->getSchool()->getId()) . ':';
        $s .= $this->getSegment() . '] ' . $this->getName();

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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->Name;
    }

    /**
     * @param string|null $Name
     */
    public function setName(?string $Name): void
    {
        $this->Name = $Name;
    }

    /**
     * @return User
     */
     public function getUser(): User
    {
        return $this->User;
    }

    /**
     * @param User $User
     */
    public function setUser(User $User): void
    {
        $this->User = $User;
    }

    /**
     * @return School
     */
    public function getSchool(): School
    {
        return $this->School;
    }

    /**
     * @param School $School
     */
    public function setSchool(School $School): void
    {
        $this->School = $School;
    }

    /**
     * @return string|null
     */
    public function getSegment(): ?string
    {
        return $this->Segment;
    }

    /**
     * @param string|null $Segment
     */
    public function setSegment(?string $Segment): void
    {
        $this->Segment = $Segment;
    }

    /**
     * @return int
     */
    public function getStartYear(): ?int
    {
        return $this->StartYear;
    }

    /**
     * @param int|null $StartYear
     */
    public function setStartYear(?int $StartYear): void
    {
        $this->StartYear = $StartYear;
    }

    /**
     * @return int
     */
    public function getNumberStudents(): ?int
    {
        return $this->NumberStudents;
    }

    /**
     * @param int|null $NumberStudents
     */
    public function setNumberStudents(?int $NumberStudents): void
    {
        $this->NumberStudents = $NumberStudents;
    }

    /**
     * @return string|null
     */
    public function getFood(): ?string
    {
        return $this->Food;
    }

    /**
     * @param string|null $Food
     */
    public function setFood(?string $Food): void
    {
        $this->Food = $Food;
    }

    /**
     * @return string|null
     */
    public function getInfo(): ?string
    {
        return $this->Info;
    }

    /**
     * @param string|null $Info
     */
    public function setInfo(?string $Info): void
    {
        $this->Info = $Info;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->Status;
    }

    /**
     * @param int $Status
     */
    public function setStatus(int $Status): void
    {
        $this->Status = $Status;
    }


}