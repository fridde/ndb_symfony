<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ReflectionClass;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    /** @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $FirstName;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $LastName;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Mobil;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Mail;

    /** @ORM\ManyToOne(targetEntity="School", inversedBy="Users")     * */
    protected School $School;

    /** @ORM\Column(type="smallint", nullable=true) */
    protected ?int $Role;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Acronym;

    /** @ORM\Column(type="smallint") */
    protected int $Status = 1;

    /** @ORM\Column(type="datetime")
     *  @Gedmo\Timestampable(on="create")
     */
    protected \DateTime $Created;

    /** @ORM\OneToMany(targetEntity="Group", mappedBy="User")
     */
    protected Collection $Groups;

    /** @ORM\ManyToMany(targetEntity="Visit", mappedBy="Colleagues")
     */
    protected Collection $Visits;

    /** @ORM\OneToMany(targetEntity="Message", mappedBy="User")
     */
    protected Collection $Messages;

    /** @ORM\OneToMany(targetEntity="Note", mappedBy="User")
     */
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
    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    /**
     * @param string|null $FirstName
     */
    public function setFirstName(?string $FirstName): void
    {
        $this->FirstName = $FirstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    /**
     * @param string|null $LastName
     */
    public function setLastName(?string $LastName): void
    {
        $this->LastName = $LastName;
    }

    /**
     * @return string|null
     */
    public function getMobil(): ?string
    {
        return $this->Mobil;
    }

    /**
     * @param string|null $Mobil
     */
    public function setMobil(?string $Mobil): void
    {
        $this->Mobil = $Mobil;
    }

    public function hasMobil(): bool
    {
        return !empty($this->getMobil());
    }

    /**
     * @return string|null
     */
    public function getMail(): ?string
    {
        return $this->Mail;
    }

    /**
     * @param string|null $Mail
     */
    public function setMail(?string $Mail): void
    {
        $this->Mail = strtolower(trim($Mail));
    }

    /**
     * @return School
     */
    public function getSchool(): School
    {
        return $this->School;
    }

    public function getSchoolId(): string
    {
        return $this->getSchool()->getId();
    }

    /**
     * @param School $School
     */
    public function setSchool(School $School): void
    {
        $this->School = $School;
    }

    /**
     * @return int|null
     */
    public function getRole(): ?int
    {
        return $this->Role;
    }

    /**
     * @param int|null $Role
     */
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

    /**
     * @return string|null
     */
    public function getAcronym(): ?string
    {
        return $this->Acronym;
    }

    /**
     * @param string|null $Acronym
     */
    public function setAcronym(?string $Acronym): void
    {
        $this->Acronym = $Acronym;
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
}