<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Data;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

trait DataFeatureTrait
{
    public function addValue($name, QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        $this->data->query($name, $queryObject, $displayCriteria);

        return $this;
    }

    public function getValue($name)
    {
        return $this->data->get($name);
    }
}
