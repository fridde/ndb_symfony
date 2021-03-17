<?php

namespace App\Entity;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CalendarEventRepository")
 * @ORM\Table(name="calendar_events")
 */
class CalendarEvent
{
    /** @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected ?int $id = null;

    /** @ORM\Column(type="datetime") */
    protected DateTime $StartDateTime;


    /** @ORM\Column(type="datetime", nullable=true) */
    protected ?DateTime $EndDateTime;

    /** @ORM\Column(type="boolean") */
    protected bool $isAllDay = true;

    /** @ORM\Column(type="string") */
    protected string $Title;

    /** @ORM\Column(type="text", nullable=true) */
    protected ?string $Description;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Location;

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
     * @return DateTime
     */
    public function getStartDateTime(): DateTime
    {
        return $this->StartDateTime;
    }

    /**
     * @param DateTime $StartDateTime
     */
    public function setStartDateTime(DateTime $StartDateTime): void
    {
        $this->StartDateTime = $StartDateTime;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDateTime(): ?DateTime
    {
        return $this->EndDateTime;
    }

    /**
     * @param DateTime|null $EndDateTime
     */
    public function setEndDateTime(?DateTime $EndDateTime): void
    {
        $this->EndDateTime = $EndDateTime;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->Title;
    }

    /**
     * @param string $Title
     */
    public function setTitle(string $Title): void
    {
        $this->Title = $Title;
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
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->Location;
    }

    /**
     * @param string|null $Location
     */
    public function setLocation(?string $Location): void
    {
        $this->Location = $Location;
    }

    /**
     * @return bool
     */
    public function isAllDay(): bool
    {
        return $this->isAllDay;
    }

    /**
     * @param bool $isAllDay
     */
    public function setIsAllDay(bool $isAllDay = true): void
    {
        $this->isAllDay = $isAllDay;
    }


}