<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Data
{
    private $data;

    private $queryExecutor;

    public function __construct(QueryExecutorInterface $queryExecutor)
    {
        $this->data = [];
        $this->queryExecutor = $queryExecutor;
    }

    public function findOne(QueryObjectInterface $queryObject, $findOr404 = false, $name = null)
    {
        $object = $this->doFindOne($queryObject);
        if ($name) {
            $this->data[$name] = $object;
        }

        if (!$object && $findOr404) {
            throw new NotFoundHttpException();
        }

        return $object;
    }

    public function find(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria, $name = null)
    {
        $objects = $this->doFind($queryObject, $displayCriteria);
        if ($name) {
            $this->data[$name] = $objects;
        }

        return $objects;
    }

    public function count(QueryObjectInterface $queryObject, $name = null)
    {
        $count = $this->doCount($queryObject);
        if ($name) {
            $this->data[$name] = $count;
        }

        return $count;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    public function execute(QueryObjectInterface $queryObject, $name = null)
    {
        $result = $this->doExecute($queryObject);
        if ($name) {
            $this->data[$name] = $result;
        }

        return $result;
    }

    protected function doFindOne(QueryObjectInterface $queryObject)
    {
        return $this->queryExecutor->findOne($queryObject);
    }

    protected function doFind(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria)
    {
        return $this->queryExecutor->find($queryObject, $displayCriteria);
    }

    protected function doCount(QueryObjectInterface $queryObject)
    {
        return $this->queryExecutor->count($queryObject);
    }

    protected function doExecute(QueryObjectInterface $queryObject)
    {
        return $this->queryExecutor->execute($queryObject);
    }
}