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

    /*
     * response
     * prava
     *
     * load primarnich dat
     * flash message
     * error stavy 500, 404, 401, 403..
     *
     * command (klasicky command, neni abstraktni)
     *      form
     *      object (vyresi: delete, patch, ...)
     *      batch
     * query (klasicky command, neni abstraktni - napr pouze pro sablonu, nebo presmerovani apod...)
     *      component (melo by byt preferovane)
     *      listing
     *      show
     */
}
