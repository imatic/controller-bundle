<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Symfony\Component\HttpFoundation\Response;

class BatchController extends ResourceController
{
    public function batchAction()
    {
        return new Response(__CLASS__);
    }
}
