<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @ORM\Table(name="messages")
 */
class Message
{
    /** @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /** @ORM\ManyToOne(targetEntity="User", inversedBy="Messages")     * */
    protected User $User;

    /** @ORM\Column(type="smallint") */
    protected int $Subject;

    /** @ORM\Column(type="smallint") */
    protected int $Carrier;

    /** @ORM\Column(type="datetime") */
    protected \DateTime $Date;

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
     * @return int
     */
    public function getSubject(): int
    {
        return $this->Subject;
    }

    /**
     * @param int $Subject
     */
    public function setSubject(int $Subject): void
    {
        $this->Subject = $Subject;
    }

    /**
     * @return int
     */
    public function getCarrier(): int
    {
        return $this->Carrier;
    }

    /**
     * @param int $Carrier
     */
    public function setCarrier(int $Carrier): void
    {
        $this->Carrier = $Carrier;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->Date;
    }

    /**
     * @param DateTime $Date
     */
    public function setDate(DateTime $Date): void
    {
        $this->Date = $Date;
    }


}