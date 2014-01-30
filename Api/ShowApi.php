<?php

namespace Imatic\Bundle\ControllerBundle\Api;

use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Response;

class ShowApi extends QueryApi
{
    public function show(QueryObjectInterface $queryObject)
    {
        $this->template->setTemplateVariableName('item');
        $this->data->addQuery('item', $queryObject);

        return $this;
    }

    public function getResponse()
    {
        $item = $this->findOr404('item');
        $this->template->addTemplateVariable($this->template->getTemplateVariableName(), $item);

        return new Response($this->template->render());
    }
}
