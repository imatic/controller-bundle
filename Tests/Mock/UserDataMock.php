<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Mock;

use Imatic\Bundle\ControllerBundle\Api\Feature\Data;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Model\User;
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
