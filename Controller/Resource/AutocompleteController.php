<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Symfony\Component\HttpFoundation\Response;

class AutocompleteController extends ResourceController
{
    public function autocompleteAction()
    {
        return new Response(__CLASS__);
    }
}
