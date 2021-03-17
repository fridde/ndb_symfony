<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="systemstatus")
 */
class SystemStatus
{
    /** @ORM\Id
     * @ORM\Column(type="string")
     */
    protected string $id;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Value;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getValue(): ?string
    {
        return $this->Value;
    }

    public function setValue(?string $Value): void
    {
        $this->Value = $Value;
    }
}