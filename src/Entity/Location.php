<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: LocationRepository::class), ORM\Table(name: "locations")]
class Location
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected int $id;

    #[ORM\Column]
    protected string $Name;

    #[ORM\Column(nullable: true)]
    protected ?string $Coordinates;

    #[ORM\Column(nullable: true)]
    protected ?string $Description;

    #[ORM\Column(type: Types::SMALLINT, unique: true), ORM\GeneratedValue]
    protected int $BusId;

    #[ORM\OneToMany(mappedBy: "Location", targetEntity: Topic::class)]
    protected Collection $Topics;


    public function __construct()
    {
        $this->Topics = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getName(): string
    {
        return $this->Name;
    }


    public function setName(string $Name): void
    {
        $this->Name = $Name;
    }


    public function getCoordinates(): ?string
    {
        return $this->Coordinates;
    }


    public function setCoordinates(?string $Coordinates): void
    {
        $this->Coordinates = $Coordinates;
    }


    public function getDescription(): ?string
    {
        return $this->Description;
    }


    public function setDescription(?string $Description): void
    {
        $this->Description = $Description;
    }


    public function getBusId(): int
    {
        return $this->BusId;
    }


    public function setBusId(int $BusId): void
    {
        $this->BusId = $BusId;
    }
}