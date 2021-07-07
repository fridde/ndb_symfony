<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ReflectionClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: UserRepository::class), ORM\Table(name: "users")]
class User implements UserInterface, \JsonSerializable
{
    use DefaultSerializable;

    public array $standard_members = ['id', 'FirstName', 'LastName', 'Mobil', 'Mail', 'Acronym'];

    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected int $id;

    #[ORM\Column(nullable: true)]
    protected ?string $FirstName;

    #[ORM\Column(nullable: true)]
    protected ?string $LastName;

    #[ORM\Column(nullable: true)]
    protected ?string $Mobil;

    #[ORM\Column(nullable: true)]
    protected ?string $Mail;

    #[ORM\ManyToOne(inversedBy: "Users")]
    protected School $School;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    protected ?int $Role;

    #[ORM\Column(nullable: true), Groups(array("basic"))]
    protected ?string $Acronym;

    #[ORM\Column(type: Types::SMALLINT)]
    protected int $Status = 1;

    // TODO: Implement timestampable Gedmo on=create
    #[ORM\Column]
    protected \DateTime $Created;

    #[ORM\OneToMany(mappedBy: "User", targetEntity: Group::class)]
    protected Collection $Groups;

    #[ORM\ManyToMany(targetEntity: Visit::class, mappedBy: "Colleagues")]
    protected Collection $Visits;

    #[ORM\OneToMany(mappedBy: "User", targetEntity: Message::class)]
    protected Collection $Messages;

    #[ORM\OneToMany(mappedBy: "User", targetEntity: Note::class)]
    protected Collection $Notes;

    public const ROLE_PENDING_USER = 0;
    public const ROLE_CONFIRMED_USER = 1;
    public const ROLE_SCHOOL_ADMIN = 2;
    public const ROLE_ADMIN = 3;

    public function __construct()
    {
        $this->Visits = new ArrayCollection();
        $this->Messages = new ArrayCollection();
        $this->Groups = new ArrayCollection();
        $this->Notes = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(?string $FirstName): void
    {
        $this->FirstName = $FirstName;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(?string $LastName): void
    {
        $this->LastName = $LastName;
    }

    public function getMobil(): ?string
    {
        return $this->Mobil;
    }

    public function setMobil(?string $Mobil): void
    {
        $this->Mobil = $Mobil;
    }

    public function hasMobil(): bool
    {
        return !empty($this->getMobil());
    }

    public function getMail(): ?string
    {
        return $this->Mail;
    }

    public function setMail(?string $Mail): void
    {
        $this->Mail = strtolower(trim($Mail));
    }

    public function getSchool(): School
    {
        return $this->School;
    }

    public function getSchoolId(): string
    {
        return $this->getSchool()->getId();
    }

    public function setSchool(School $School): void
    {
        $this->School = $School;
    }

    public function getRole(): ?int
    {
        return $this->Role;
    }

    public function setRole(?int $Role): void
    {
        $this->Role = $Role;
    }

    public function getRoleString(): ?string
    {
        return self::convertRoleToString($this->getRole());
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        $roles[] = $this->getRoleString();

        return array_filter(array_unique($roles));
    }

    public function isPending(): bool
    {
        return $this->getRole() === self::ROLE_PENDING_USER;
    }

    public function getAcronym(): ?string
    {
        return $this->Acronym;
    }

    public function setAcronym(?string $Acronym): void
    {
        $this->Acronym = $Acronym;
    }

    public function getStatus(): int
    {
        return $this->Status;
    }

    public function setStatus(int $Status): void
    {
        $this->Status = $Status;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->Created;
    }

    /**
     * @param \DateTime $Created
     */
    public function setCreated(\DateTime $Created): void
    {
        $this->Created = $Created;
    }


    /**
     * @return array The roles as [0 => 'ROLE_PENDING_USER', 1 => 'ROLE_CONFIRMED_USER', ...]
     */
    public static function getRoleArray(): array
    {
        return array_flip(self::getRoleLabels());
    }

    public static function convertRoleToString(?int $role): ?string
    {
        return $role === null ? null : self::getRoleArray()[$role];
    }


    /**
     * @return array ['ROLE_PENDING_USER' => 0, 'ROLE_CONFIRMED_USER' => 1, ...]
     */
    public static function getRoleLabels(): array
    {
        return array_filter(
            (new ReflectionClass(__CLASS__))->getConstants(),
            fn($c) => str_starts_with($c, 'ROLE'),
            ARRAY_FILTER_USE_KEY
        );
    }

    public function isAdmin(): bool
    {
        return $this->getRole() === self::ROLE_ADMIN;
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->getMail();
    }

    public function eraseCredentials(): void
    {
    }

    public function jsonSerialize(): array
    {
        $return = $this->getStandardMembers();

        $return['School'] = $this->getSchoolId();

        return $return;
    }


    /** Necessary to implement Symfony\Component\Security\Core\User\UserInterface   */
    public function getUserIdentifier(): string
    {
        return $this->getMail();
    }
}