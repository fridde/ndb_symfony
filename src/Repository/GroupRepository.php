<?php

namespace App\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository
{
    use Filterable;

    public array $filter_translator = [
        'active' => 'isActive'
    ];

    /** @noinspection NullPointerExceptionInspection */
    public function isActive(bool $active): void
    {
        $status = (int) $active;
        $exp = Criteria::expr()->eq('Status', $status);
        $this->criteria->andWhere($exp);
    }
}