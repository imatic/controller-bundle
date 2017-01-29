<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Symfony\Component\HttpFoundation\Response;

class DeleteController extends ResourceController
{
    public function deleteAction()
    {
        return new Response(__CLASS__);
    }
}
