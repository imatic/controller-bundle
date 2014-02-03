<?php

namespace Imatic\Bundle\ControllerBundle\Api;

use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Response;

class ShowApi extends QueryApi
{
    public function show(QueryObjectInterface $queryObject)
    {
        $this->data->findOne($queryObject, true, 'item');

        return $this;
    }

    public function getResponse()
    {
        $this->template->addTemplateVariables($this->data->all());

        return new Response($this->template->render());
    }
}
