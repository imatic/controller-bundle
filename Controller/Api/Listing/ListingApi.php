<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Listing;

use Imatic\Bundle\ControllerBundle\Controller\Api\Query\QueryApi;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Response;

class ListingApi extends QueryApi
{
    protected $filter;

    protected $defaultSorter;

    protected $queryObject;

    protected $displayCriteria;

    public function listing(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        $this->displayCriteria = $displayCriteria;
        $this->queryObject = $queryObject;

        return $this;
    }

    public function filter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function getResponse()
    {
        if (null === $this->displayCriteria) {
            $dcOptions = [];
            if ($this->filter) {
                $dcOptions['filter'] = $this->filter;
            }
            $this->displayCriteria = $this->request->getDisplayCriteria($dcOptions);
        }

        $this->data->query('items', $this->queryObject, $this->displayCriteria);
        $this->data->count('items_count', $this->queryObject);

        $this->displayCriteria->getPager()->setTotal($this->data->get('items_count'));
        $this->template->addTemplateVariable('display_criteria', $this->displayCriteria);

        $this->template->addTemplateVariables($this->data->all());

        return new Response($this->template->render());
    }
}
