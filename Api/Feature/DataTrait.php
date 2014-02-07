<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

trait DataTrait
{
    public function addData($name, QueryObjectInterface $queryObject)
    {
        $this->data->addData($name, $queryObject);

        return $this;
    }
}
