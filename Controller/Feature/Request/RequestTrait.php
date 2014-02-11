<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

use Symfony\Component\HttpFoundation\Request;

trait RequestTrait
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     * @return $this
     */
    public function useRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return Request
     */
    protected function getRequest()
    {
        return $this->request;
    }
}
