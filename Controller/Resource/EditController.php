<?php

namespace Imatic\Bundle\DirectoryBundle\Controller\Crud;

use Symfony\Component\HttpFoundation\Response;

class EditController extends ResourceController
{
    public function listAction()
    {
        return new Response(__CLASS__);
    }
}
