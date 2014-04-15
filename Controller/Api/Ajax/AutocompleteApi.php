<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Ajax;

use Imatic\Bundle\ControllerBundle\Controller\Api\Query\QueryApi;
use Imatic\Bundle\ControllerBundle\Form\Type\Filter\AutocompleteFilter;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AutocompleteApi extends QueryApi
{
    protected $queryObject;

    protected $labelFunction;

    protected $identifierFunction;

    public function autocomplete(QueryObjectInterface $queryObject)
    {
        $this->labelFunction = function ($item) {
            return (string)$item;
        };
        $this->identifierFunction = function ($item) {
            return $item->getId();
        };

        $this->queryObject = $queryObject;

        return $this;
    }

    public function labelFunction(\Closure $function)
    {
        $this->labelFunction = $function;

        return $this;
    }

    public function identifierFunction(\Closure $function)
    {
        $this->identifierFunction = $function;

        return $this;
    }

    public function getResponse()
    {
        $displayCriteria = $this->request->getDisplayCriteria(['filter' => new AutocompleteFilter()]);

        $items = $this->data->query('items', $this->queryObject, $displayCriteria);
        $result = array();
        foreach ($items as $item) {
            $result[] = array(
                'id' => call_user_func($this->identifierFunction, $item),
                'text' => call_user_func($this->labelFunction, $item),
            );
        }

        return new JsonResponse($result);
    }
}