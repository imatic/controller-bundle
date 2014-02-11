<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Data;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

trait DataTrait
{
    public function addValue($name, QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria)
    {
        $this->data->query($name, $queryObject, $displayCriteria);

        return $this;
    }
}
