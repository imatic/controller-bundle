<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api;

abstract class Api
{
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
