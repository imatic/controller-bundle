<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Show;

use Imatic\Bundle\ControllerBundle\Controller\Api\QueryApi;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowApi extends QueryApi
{
    public function show(SingleResultQueryObjectInterface $queryObject)
    {
        $result = $this->data->query('item',  $queryObject);
        if (!$result) {
            throw new NotFoundHttpException();
        }

        return $this;
    }

    public function getResponse()
    {
        $this->template->addTemplateVariables($this->data->all());

        return new Response($this->template->render());
    }
}
