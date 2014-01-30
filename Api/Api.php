<?php

namespace Imatic\Bundle\ControllerBundle\Api;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     */
}