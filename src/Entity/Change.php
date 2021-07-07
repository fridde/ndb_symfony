<?php

namespace App\Entity;

use App\Repository\ChangeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChangeRepository::class), ORM\Table(name: "changes")]
class Change
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected int $id;
}