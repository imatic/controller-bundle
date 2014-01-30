<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class Data
{
    private $queryStack;

    private $queryExecutor;

    public function __construct(QueryExecutorInterface $queryExecutor)
    {
        $this->queryStack = [];
        $this->queryExecutor = $queryExecutor;
    }

    public function addQuery($name, QueryObjectInterface $queryObject)
    {
        $this->queryStack[$name] = $queryObject;
    }

    public function findOne($name)
    {
        return $this->doFindOne($this->queryStack[$name]);
    }

    public function find($name, DisplayCriteriaInterface $displayCriteria)
    {
        return $this->doFind($this->queryStack[$name], $displayCriteria);
    }

    public function count($name)
    {
        return $this->queryExecutor->count($this->queryStack[$name]);
    }

    protected function doFindOne(QueryObjectInterface $queryObject)
    {
        return $this->queryExecutor->findOne($queryObject);
    }

    protected function doFind(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria)
    {
        return $this->queryExecutor->find($queryObject, $displayCriteria);
    }
}