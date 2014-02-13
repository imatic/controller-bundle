<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;

abstract class Api
{
    /**
     * @var ResponseFeature
     */
    protected $response;

    /**
     * @var RequestFeature
     */
    protected $request;

    public function __construct(RequestFeature $request, ResponseFeature $response)
    {
        $this->response = $response;
        $this->request = $request;
    }

    abstract public function getResponse();
}
