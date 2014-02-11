<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Mock;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\Data;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserListQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Model\User;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;

class UserDataMock extends Data
{
    public function __construct()
    {
    }

    protected function doQuery(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        if ($queryObject instanceof SingleResultQueryObjectInterface) {
            /** @var $queryObject UserQuery */
            return new User($queryObject->id);
        } else {
            $return = [];
            /** @var $queryObject UserListQuery */
            for ($i = 1; $i <= $queryObject->limit; $i++) {
                $return[$i] = new User($i);
            }

            return $return;
        }
    }
}
