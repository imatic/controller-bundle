<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Listing;

use Imatic\Bundle\ControllerBundle\Controller\Api\Query\QueryApi;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Response;

class ListingApi extends QueryApi
{
    public function listing(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        // todo: pokud neni predano $displayCriteria tak nacist automaticky
        $this->data->query('items', $queryObject, $displayCriteria);

        return $this;
    }

    public function getResponse()
    {
        $this->template->addTemplateVariables($this->data->all());

        return new Response($this->template->render());
    }
}
