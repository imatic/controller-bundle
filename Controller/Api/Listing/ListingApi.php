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
        if (null === $displayCriteria) {
            $displayCriteria = $this->request->getDisplayCriteria();
        }

        $this->data->query('items', $queryObject, $displayCriteria);
        $this->data->count('items_count', $queryObject);

        $displayCriteria->getPager()->setTotal($this->data->get('items_count'));
        $this->template->addTemplateVariable('display_criteria', $displayCriteria);

        return $this;
    }

    public function getResponse()
    {
        $this->template->addTemplateVariables($this->data->all());

        return new Response($this->template->render());
    }
}
