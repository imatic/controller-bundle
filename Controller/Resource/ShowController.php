<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Symfony\Component\HttpFoundation\Response;

class ShowController extends ResourceController
{
    public function showAction()
    {
        return new Response(__CLASS__);
    }
}
