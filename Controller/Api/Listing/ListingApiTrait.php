<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Listing;

trait ListingApiTrait
{
    /**
     * @return ListingApi
     */
    public function listing()
    {
        return $this->getApi('listing', func_get_args());
    }
}
