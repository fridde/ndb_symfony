<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicRepository")
 * @ORM\Table(name="topics")
 */
class Topic
{
    /** @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Segment;

    /** @ORM\Column(type="smallint")
     */
    protected int $VisitOrder = -1;

    /** @ORM\Column(type="string") */
    protected string $ShortName;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $LongName;

    /** @ORM\ManyToOne(targetEntity="Location", inversedBy="Topics")     * */
    protected Location $Location;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Food;

    /** @ORM\Column(type="smallint", nullable=true) */
    protected ?int $FoodOrder;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Url;

    /** @ORM\OneToMany(targetEntity="Visit", mappedBy="Topic")   */
    protected Collection $Visits;

    public const SEGMENT_2 = '2';
    public const SEGMENT_5 = '5';
    public const SEGMENT_9 = '9';
    public const SEGMENT_fri = 'fri';

    /**
     * Topic constructor.
     */
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
     * @return string|null
     */
    public function getSegment(): ?string
    {
        return $this->Segment;
    }

    /**
     * @param string|null $Segment
     */
    public function setSegment(?string $Segment): void
    {
        $this->Segment = $Segment;
    }

    /**
     * @return int
     */
    public function getVisitOrder(): int
    {
        return $this->VisitOrder;
    }

    /**
     * @param int $VisitOrder
     */
    public function setVisitOrder(int $VisitOrder): void
    {
        $this->VisitOrder = $VisitOrder;
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        return $this->ShortName;
    }

    /**
     * @param string $ShortName
     */
    public function setShortName(string $ShortName): void
    {
        $this->ShortName = $ShortName;
    }

    /**
     * @return string|null
     */
    public function getLongName(): ?string
    {
        return $this->LongName;
    }

    /**
     * @param string|null $LongName
     */
    public function setLongName(?string $LongName): void
    {
        $this->LongName = $LongName;
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->Location;
    }

    /**
     * @param Location $Location
     */
    public function setLocation(Location $Location): void
    {
        $this->Location = $Location;
    }

    /**
     * @return string|null
     */
    public function getFood(): ?string
    {
        return $this->Food;
    }

    /**
     * @param string|null $Food
     */
    public function setFood(?string $Food): void
    {
        $this->Food = $Food;
    }

    /**
     * @return int|null
     */
    public function getFoodOrder(): ?int
    {
        return $this->FoodOrder;
    }

    /**
     * @param int|null $FoodOrder
     */
    public function setFoodOrder(?int $FoodOrder): void
    {
        $this->FoodOrder = $FoodOrder;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->Url;
    }

    /**
     * @param string|null $Url
     */
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