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

    public function getCurrentRoute()
    {
        return $this->getCurrentRequest()->get('_route');
    }

    public function getCurrentUri()
    {
        return $this->getCurrentRequest()->getUri();
    }
}
