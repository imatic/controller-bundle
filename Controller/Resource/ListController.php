<?php

namespace Imatic\Bundle\DirectoryBundle\Controller\Crud;

use Symfony\Component\HttpFoundation\Response;

class ListController extends ResourceController
{
    public function listAction()
    {
        return new Response(__CLASS__);
    }
}
