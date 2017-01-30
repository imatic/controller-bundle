<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\Listing\ListingApi;

class ListController extends ResourceController
{
    /**
     * @return ListingApi
     */
    protected function listing()
    {
        return $this->getApi('imatic_controller.api.listing', 'listing', func_get_args());
    }

    public function listAction()
    {
        $action = $this->getActionConfig();

        return $this
            ->listing(new $action['query']($action['entity']))
//            ->filter('imatic_directory_company_filter')
            ->setTemplateName($action['template'])
            ->addTemplateVariable('action', $action)
            ->addTemplateVariable('resource', $this->getResourceConfig())
            ->getResponse();
    }
}
