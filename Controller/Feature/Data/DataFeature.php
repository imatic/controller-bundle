<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Data;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class DataFeature
{
    private $data;

    private $queryExecutor;

    public function __construct(QueryExecutorInterface $queryExecutor)
    {
        $this->data = [];
        $this->queryExecutor = $queryExecutor;
    }

    /**
     * @param string                   $name
     * @param QueryObjectInterface     $queryObject
     * @param DisplayCriteriaInterface $displayCriteria
     *
     * @return mixed
     */
    public function query($name, QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        $result = $this->doQuery($queryObject, $displayCriteria);
        $this->data[$name] = $result;

        return $result;
    }

    /**
     * @param string                   $name
     * @param QueryObjectInterface     $queryObject
     * @param DisplayCriteriaInterface $displayCriteria
     *
     * @return int
     */
    public function count($name, QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        $count = $this->doCount($queryObject, $displayCriteria);
        $this->data[$name] = $count;

        return $count;
    }

    /**
     * @param string                   $resultName
     * @param string                   $countName
     * @param QueryObjectInterface     $queryObject
     * @param DisplayCriteriaInterface $displayCriteria
     *
     * @return array result, count
     */
    public function queryAndCount($resultName, $countName, QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        list($result, $count) = $this->doQueryAndCount($queryObject, $displayCriteria);

        $this->data[$resultName] = $result;
        $this->data[$countName] = $count;

        return [$result, $count];
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    public function get($name, $default = null)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return $default;
    }

    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    protected function doQuery(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        return $this->queryExecutor->execute($queryObject, $displayCriteria);
    }

    protected function doCount(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        return $this->queryExecutor->count($queryObject, $displayCriteria);
    }

    protected function doQueryAndCount(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        return $this->queryExecutor->executeAndCount($queryObject, $displayCriteria);
    }
}
