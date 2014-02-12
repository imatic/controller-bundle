<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Show;

use Imatic\Bundle\ControllerBundle\Controller\Api\Query\QueryApi;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use Symfony\Component\HttpFoundation\Response;

class ShowApi extends QueryApi
{
    public function show(SingleResultQueryObjectInterface $queryObject)
    {
        $result = $this->data->query('item', $queryObject);
        $this->response->throwNotFoundUnless($result);

        return $this;
    }

    public function getResponse()
    {
        $this->template->addTemplateVariables($this->data->all());

        return new Response($this->template->render());
    }
}
