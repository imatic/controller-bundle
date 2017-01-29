<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Symfony\Component\HttpFoundation\Response;

class ListController extends ResourceController
{
    public function listAction()
    {
        return new Response(__CLASS__);
    }
}
