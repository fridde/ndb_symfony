<?php

namespace App\Entity;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SchoolRepository")
 * @ORM\Table(name="schools")
 */
class School
{
    /** @ORM\Id
     * @ORM\Column(type="string")
     */
    protected string $id;

    /** @ORM\Column(type="string") */
    protected string $Name;

    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $Coordinates;

    /** @ORM\Column(type="smallint") */
    protected int $VisitOrder;

    /** @ORM\Column(type="integer", options={"default" : 0}) */
    protected int $BusRule = 0;

    /** @ORM\Column(type="smallint", options={"default" : 1}) */
    protected int $FoodRule = 1;

    /** @ORM\Column(type="smallint") */
    protected int $Status = 1;

    /** @ORM\OneToMany(targetEntity="Group", mappedBy="School")
     * @ORM\OrderBy({"Name" = "ASC"})
     **/
    protected Collection $Groups;

    /** @ORM\OneToMany(targetEntity="User", mappedBy="School")
     * * @ORM\OrderBy({"FirstName" = "ASC"})
     */
    protected Collection $Users;

    public const FOOD_NONE = 0;
    public const FOOD_AUTO = 1;
    public const FOOD_ORDER = 2;

    public function __construct()
    {
        $this->Groups = new ArrayCollection();
        $this->Users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName($Name): void
    {
        $this->Name = $Name;
    }

    public function getCoordinates(): string
    {
        return $this->Coordinates;
    }

    public function setCoordinates(?string $Coordinates): void
    {
        $this->Coordinates = $Coordinates;
    }

    public function getVisitOrder(): int
    {
        return $this->VisitOrder;
    }

    public function setVisitOrder(int $VisitOrder): void
    {
        $this->VisitOrder = $VisitOrder;
    }

    public function getBusRule(): int
    {
        return $this->BusRule;
    }

    public function setBusRule(int $BusRule): void
    {
        $this->BusRule = $BusRule;
    }

    public function getFoodRule(): int
    {
        return $this->FoodRule;
    }

    public function setFoodRule(int $FoodRule): void
    {
        $this->FoodRule = $FoodRule;
    }

    public function addLocationToBusRule(Location $location): void
    {
        $bus_rule = $this->getBusRule();
        $location_bus_value = 1 << $location->getBusId();

        $new_bus_rule = $bus_rule | $location_bus_value; // bitwise
        $this->setBusRule($new_bus_rule);
    }

    public function removeLocationFromBusRule(Location $location): void
    {
        $bus_rule = $this->getBusRule();
        $location_bus_value = 1 << $location->getBusId();

        if ($bus_rule & $location_bus_value) {  // i.e. needs bus
            $bus_rule ^= $location_bus_value; // removes the corresponding bit
        }
        $this->setBusRule($bus_rule);
    }

    public function updateBusRule(Location $location, bool $needs_bus): void
    {
        if ($needs_bus) {
            $this->addLocationToBusRule($location);
        } else {
            $this->removeLocationFromBusRule($location);
        }
    }

    public function needsBus(Location $location = null): bool
    {
        if($location === null){
            return false;
        }
        $location_bus_value = 1 << $location->getBusId();

        return $this->getBusRule() & $location_bus_value; // bitwise
    }

    public function getStatus(): int
    {
        return $this->Status;
    }

    public function setStatus(int $status): void
    {
        $this->Status = $status;
    }

    public function getGroups(): array
    {
        return $this->Groups->toArray();
    }

    public function getGroupsByName(): array
    {
        return $this->getGroups();

    }


    public function getUsers(): array
    {
        return $this->Users->toArray();
    }

    public function addUser($User)
    {
        $this->Users->add($User);
    }

    /**
     * @param $segment
     * @param mixed $start_year If null, the current year is assumed. If false, all years are included
     * @return array
     */
    public function getActiveGroupsBySegmentAndYear(string $segment_id, $start_year = null): array
    {
        $start_year = $start_year ?? Carbon::today()->year;

        return array_filter(
            $this->getGroupsByName(),
            function (Group $g) use ($segment_id, $start_year) {
                $cond1 = $start_year !== false ? $g->getStartYear() === $start_year : true;
                $cond2 = $g->getSegment() === $segment_id;
                $cond3 = $g->isActive();

                return $cond1 && $cond2 && $cond3;
            }
        );
    }

    public function hasSegment(string $segment_id)
    {
        return !empty($this->getActiveGroupsBySegmentAndYear($segment_id, false));
    }

    public function getSegmentsAvailable($withLabels = false)
    {
        $available_segments = array_filter(
            Group::getSegmentLabels(),
            function ($k) {
                return $this->hasSegment($k);
            },
            ARRAY_FILTER_USE_KEY
        );

        return $withLabels ? $available_segments : array_keys($available_segments);
    }

    public function getNrActiveGroupsBySegmentAndYear($segment_id, $start_year = null)
    {
        return count($this->getActiveGroupsBySegmentAndYear($segment_id, $start_year));
    }

    public function isNaturskolan()
    {
        return $this->getId() === 'natu';
    }

    /**
     * @return array ['FOOD_NONE' => 0, ...]
     */
    public static function getFoodRuleLabels(): array
    {
        return array_filter(
            (new \ReflectionClass(__CLASS__))->getConstants(),
            fn($c) => str_starts_with($c, 'FOOD'),
            ARRAY_FILTER_USE_KEY
        );
    }
}