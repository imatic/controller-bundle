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
        $config = $this->getConfig();

        return $this
            ->listing(new $config['query']($config['entity']))
//            ->filter('imatic_directory_company_filter')
            ->setTemplateName($config['template'])
            ->addTemplateVariable('config', $config)
            ->getResponse();
    }
}
