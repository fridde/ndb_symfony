<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: TopicRepository::class), ORM\Table(name: "topics")]
class Topic
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected int $id;

    #[ORM\Column(nullable: true)]
    protected ?string $Segment;

    #[ORM\Column(type: Types::SMALLINT)]
    protected int $VisitOrder = -1;

    #[ORM\Column]
    protected string $ShortName;

    #[ORM\Column(nullable: true)]
    protected ?string $LongName;

    #[ORM\ManyToOne(inversedBy: "Topics")]
    protected Location $Location;

    #[ORM\Column(nullable: true)]
    protected ?string $Food;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    protected ?int $FoodOrder;

    #[ORM\Column(nullable: true)]
    protected ?string $Url;

    #[ORM\OneToMany(mappedBy: "Topic", targetEntity: Visit::class)]
    protected Collection $Visits;

    public const SEGMENT_2 = '2';
    public const SEGMENT_5 = '5';
    public const SEGMENT_9 = '9';
    public const SEGMENT_fri = 'fri';


    public function __construct()
    {
        $this->Visits = new ArrayCollection();
    }

    public function __toString()
    {
        $s = '[' . $this->getSegment();
        $visit_order = $this->getVisitOrder();
        if($visit_order !== null){
            $s .= ':' . $visit_order;
        }
        $s .= '] ' . $this->getShortName();

        return  $s;
    }



    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSegment(): ?string
    {
        return $this->Segment;
    }


    public function setSegment(?string $Segment): void
    {
        $this->Segment = $Segment;
    }


    public function getVisitOrder(): int
    {
        return $this->VisitOrder;
    }


    public function setVisitOrder(int $VisitOrder): void
    {
        $this->VisitOrder = $VisitOrder;
    }


    public function getShortName(): string
    {
        return $this->ShortName;
    }


    public function setShortName(string $ShortName): void
    {
        $this->ShortName = $ShortName;
    }


    public function getLongName(): ?string
    {
        return $this->LongName;
    }


    public function setLongName(?string $LongName): void
    {
        $this->LongName = $LongName;
    }

    public function getLocation(): Location
    {
        return $this->Location;
    }


    public function setLocation(Location $Location): void
    {
        $this->Location = $Location;
    }


    public function getFood(): ?string
    {
        return $this->Food;
    }


    public function setFood(?string $Food): void
    {
        $this->Food = $Food;
    }


    public function getFoodOrder(): ?int
    {
        return $this->FoodOrder;
    }


    public function setFoodOrder(?int $FoodOrder): void
    {
        $this->FoodOrder = $FoodOrder;
    }


    public function getUrl(): ?string
    {
        return $this->Url;
    }


    public function setUrl(?string $Url): void
    {
        $this->Url = $Url;
    }

    /**
     * @return array ['SEGMENT_2' => '2', ...]
     */
    public static function getSegmentLabels(): array
    {
        return array_filter(
            (new \ReflectionClass(__CLASS__))->getConstants(),
            fn($c) => str_starts_with($c, 'SEGMENT'),
            ARRAY_FILTER_USE_KEY
        );
    }


}