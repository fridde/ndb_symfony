<?php

namespace App\Entity;

use App\Repository\CalendarEventRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CalendarEventRepository::class)]
#[ORM\Table(name: "calendar_events")]
class CalendarEvent
{
    #[ORM\Id, ORM\Column(nullable: true), ORM\GeneratedValue]
    protected ?int $id = null;

    #[ORM\Column]
    protected DateTime $StartDateTime;

    #[ORM\Column(nullable: true)]
    protected ?DateTime $EndDateTime = null;

    #[ORM\Column]
    protected bool $isAllDay = true;

    #[ORM\Column]
    protected string $Title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $Description = null;

    #[ORM\Column(nullable: true)]
    protected ?string $Location = null;

    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getStartDateTime(): DateTime
    {
        return $this->StartDateTime;
    }

    public function setStartDateTime(DateTime $StartDateTime): void
    {
        $this->StartDateTime = $StartDateTime;
    }

    public function getEndDateTime(): ?DateTime
    {
        return $this->EndDateTime;
    }

    public function setEndDateTime(?DateTime $EndDateTime): void
    {
        $this->EndDateTime = $EndDateTime;
    }

    public function getTitle(): string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): void
    {
        $this->Title = $Title;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): void
    {
        $this->Description = $Description;
    }

    public function getLocation(): ?string
    {
        return $this->Location;
    }

    public function setLocation(?string $Location): void
    {
        $this->Location = $Location;
    }

    public function isAllDay(): bool
    {
        return $this->isAllDay;
    }

    public function setIsAllDay(bool $isAllDay = true): void
    {
        $this->isAllDay = $isAllDay;
    }


}