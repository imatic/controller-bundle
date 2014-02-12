<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Request;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestFeature
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getCurrentRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}
