<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: MessageRepository::class), ORM\Table(name: "messages")]
class Message
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected int $id;

    #[ORM\ManyToOne(inversedBy: "Messages")]
    protected User $User;

    #[ORM\Column(type: Types::SMALLINT)]
    protected int $Subject;

    #[ORM\Column(type: Types::SMALLINT)]
    protected int $Carrier;

    #[ORM\Column]
    protected \DateTime $Date;


    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getUser(): User
    {
        return $this->User;
    }


    public function setUser(User $User): void
    {
        $this->User = $User;
    }


    public function getSubject(): int
    {
        return $this->Subject;
    }


    public function setSubject(int $Subject): void
    {
        $this->Subject = $Subject;
    }


    public function getCarrier(): int
    {
        return $this->Carrier;
    }


    public function setCarrier(int $Carrier): void
    {
        $this->Carrier = $Carrier;
    }


    public function getDate(): DateTime
    {
        return $this->Date;
    }


    public function setDate(DateTime $Date): void
    {
        $this->Date = $Date;
    }


}