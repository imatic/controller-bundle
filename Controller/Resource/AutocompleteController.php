<?php

namespace Imatic\Bundle\DirectoryBundle\Controller\Crud;

use Symfony\Component\HttpFoundation\Response;

class AutocompleteController extends ResourceController
{
    public function listAction()
    {
        return new Response(__CLASS__);
    }
}
