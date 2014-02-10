<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

trait DataTrait
{
    public function addDataItem($name, QueryObjectInterface $queryObject)
    {
        $this->data->findOne($name, $queryObject);

        return $this;
    }

    public function addDataList($name, QueryObjectInterface $queryObject)
    {
        $this->data->find($name, $queryObject);

        return $this;
    }

    public function addDataResult($name, QueryObjectInterface $queryObject)
    {
        $this->data->execute($name, $queryObject);

        return $this;
    }
}
