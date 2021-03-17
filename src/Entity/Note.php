<?php

namespace App\Entity;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoteRepository")
 * @ORM\Table(name="notes")
 */
class Note
{
    /** @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /** @ORM\ManyToOne(targetEntity="Visit", inversedBy="Notes") */
    protected Visit $Visit;

    /** @ORM\ManyToOne(targetEntity="User", inversedBy="Notes") */
    protected User $User;

    /** @ORM\Column(type="string") */
    protected string $Text;

    /** @ORM\Column(type="datetime")
     *  @Gedmo\Timestampable(on="update")
     */
    protected ?DateTime $LastUpdate;

    public function __construct()
    {
        $this->setLastUpdate(Carbon::now());
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
     * @return Visit
     */
    public function getVisit(): Visit
    {
        return $this->Visit;
    }

    /**
     * @param Visit $Visit
     */
    public function setVisit(Visit $Visit): void
    {
        $this->Visit = $Visit;
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
     * @return string
     */
    public function getText(): string
    {
        return $this->Text;
    }

    /**
     * @param string $Text
     */
    public function setText(string $Text): void
    {
        $this->Text = $Text;
    }

    /**
     * @return DateTime|null
     */
    public function getLastUpdate(): ?DateTime
    {
        return $this->LastUpdate;
    }

    /**
     * @param DateTime|null $LastUpdate
     */
    public function setLastUpdate(?DateTime $LastUpdate): void
    {
        $this->LastUpdate = $LastUpdate;
    }


}