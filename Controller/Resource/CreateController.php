<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Symfony\Component\HttpFoundation\Response;

class CreateController extends ResourceController
{
    public function createAction()
    {
        return new Response(__CLASS__);
    }
}
