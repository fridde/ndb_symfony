<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 * @ORM\Table(name="locations")
 */
class Location
{
    /** @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /** @ORM\Column(type="string") */
    protected string $Name;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Coordinates;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Description;

    /** @ORM\Column(type="smallint", unique=true)
     * @ORM\GeneratedValue
     */
    protected int $BusId;

    /** @ORM\OneToMany(targetEntity="Topic", mappedBy="Location") */
    protected Collection $Topics;


    public function __construct()
    {
        $this->Topics = new ArrayCollection();
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
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     */
    public function setName(string $Name): void
    {
        $this->Name = $Name;
    }

    /**
     * @return string|null
     */
    public function getCoordinates(): ?string
    {
        return $this->Coordinates;
    }

    /**
     * @param string|null $Coordinates
     */
    public function setCoordinates(?string $Coordinates): void
    {
        $this->Coordinates = $Coordinates;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->Description;
    }

    /**
     * @param string|null $Description
     */
    public function setDescription(?string $Description): void
    {
        $this->Description = $Description;
    }

    /**
     * @return int
     */
    public function getBusId(): int
    {
        return $this->BusId;
    }

    /**
     * @param int $BusId
     */
    public function setBusId(int $BusId): void
    {
        $this->BusId = $BusId;
    }
}