<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @ORM\Table(name="groups")
 */
class Group implements \JsonSerializable
{
    use DefaultSerializable;

    public array $standard_members = ['id', 'Name', 'Segment', 'StartYear', 'NumberStudents', 'Info'];

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

    /** @ORM\ManyToOne(targetEntity="School", inversedBy="Groups")
     */
    protected School $School;

    /** @ORM\Column(type="string", nullable=true)
     */
    protected ?string $Segment;

    /** @ORM\Column(type="smallint", nullable=true)
     */
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

    /** @ORM\Column(type="smallint")
     */
    protected int $Status = self::ACTIVE;

    /** @ORM\OneToMany(targetEntity="Visit", mappedBy="Group")
     */
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

    public function __toString(): string
    {
        $s = '[' . strtoupper($this->getSchool()->getId()) . ':';
        $s .= $this->getSegment() . '] ' . $this->getName();

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


    public function getName(): ?string
    {
        return $this->Name;
    }


    public function setName(?string $Name): void
    {
        $this->Name = $Name;
    }

     public function getUser(): User
    {
        return $this->User;
    }

    public function setUser(User $User): void
    {
        $this->User = $User;
    }

    public function getSchool(): School
    {
        return $this->School;
    }

    public function setSchool(School $School): void
    {
        $this->School = $School;
    }

    public function getSegment(): ?string
    {
        return $this->Segment;
    }

    public function setSegment(?string $Segment): void
    {
        $this->Segment = $Segment;
    }

    public function getStartYear(): ?int
    {
        return $this->StartYear;
    }

    public function setStartYear(?int $StartYear): void
    {
        $this->StartYear = $StartYear;
    }

    public function getNumberStudents(): ?int
    {
        return $this->NumberStudents;
    }

    public function setNumberStudents(?int $NumberStudents): void
    {
        $this->NumberStudents = $NumberStudents;
    }

    public function getFood(): ?string
    {
        return $this->Food;
    }

    public function setFood(?string $Food): void
    {
        $this->Food = $Food;
    }

    public function getInfo(): ?string
    {
        return $this->Info;
    }

     public function setInfo(?string $Info): void
    {
        $this->Info = $Info;
    }

    public function getStatus(): int
    {
        return $this->Status;
    }

    public function setStatus(int $Status): void
    {
        $this->Status = $Status;
    }


    public function jsonSerialize(): array
    {
        $return = $this->getStandardMembers();

        $return['School'] = $this->getSchool()->getId();

        return $return;
    }
}