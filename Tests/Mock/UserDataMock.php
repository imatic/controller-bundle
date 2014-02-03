<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Mock;

use Imatic\Bundle\ControllerBundle\Api\Feature\Data;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\Data\UserListQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\Data\UserQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\Entity\User;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class UserDataMock extends Data
{
    public function __construct()
    {
    }

    protected function doFindOne(QueryObjectInterface $queryObject)
    {
        /** @var $queryObject UserQuery */
        return new User($queryObject->id);
    }

    protected function doFind(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria)
    {
        $return = [];
        /** @var $queryObject UserListQuery */
        for ($i = 1; $i <= $queryObject->limit; $i++) {
            $return[$i] = new User($i);
        }

        return $return;
    }
}
