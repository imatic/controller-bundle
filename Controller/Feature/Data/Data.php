<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Data;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class Data
{
    private $data;

    private $queryExecutor;

    public function __construct(QueryExecutorInterface $queryExecutor)
    {
        $this->data = [];
        $this->queryExecutor = $queryExecutor;
    }

    /**
     * @param string $name
     * @param QueryObjectInterface $queryObject
     * @param DisplayCriteriaInterface $displayCriteria
     * @return mixed
     */
    public function query($name, QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        $result = $this->doQuery($queryObject, $displayCriteria);
        $this->data[$name] = $result;

        return $result;
    }

    /**
     * @param string $name
     * @param QueryObjectInterface $queryObject
     * @return int
     */
    public function count($name, QueryObjectInterface $queryObject)
    {
        $count = $this->doCount($queryObject);
        $this->data[$name] = $count;

        return $count;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    protected function doQuery(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        return $this->queryExecutor->execute($queryObject, $displayCriteria);
    }

    protected function doCount(QueryObjectInterface $queryObject)
    {
        return $this->queryExecutor->count($queryObject);
    }
}