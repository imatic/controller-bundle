<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

trait RequestTrait
{
    public function useRequest(Request $request)
    {
        return $this;
    }

    protected function getRequest()
    {
        return $request;
    }
}