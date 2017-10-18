<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Query;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Security\SecurityFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Security\SecurityFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeature;

abstract class ItemQueryApi extends QueryApi
{
    use SecurityFeatureTrait;

    /**
     * @var SecurityFeature
     */
    protected $security;

    public function __construct(
        RequestFeature $request,
        ResponseFeature $response,
        TemplateFeature $template,
        DataFeature $data,
        SecurityFeature $security
    ) {
        parent::__construct($request, $response, $template, $data);
        $this->security = $security;
    }
}
