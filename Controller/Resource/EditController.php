<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Symfony\Component\HttpFoundation\Response;

class EditController extends ResourceController
{
    public function editAction()
    {
        return new Response(__CLASS__);
    }
}