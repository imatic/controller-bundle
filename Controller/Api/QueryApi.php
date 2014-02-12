<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeatureTrait;

abstract class QueryApi extends Api
{
    use TemplateFeatureTrait;
    use DataFeatureTrait;

    /**
     * @var DataFeature
     */
    protected $data;

    /**
     * @var TemplateFeature
     */
    protected $template;

    public function __construct(DataFeature $data, TemplateFeature $template)
    {
        $this->data = $data;
        $this->template = $template;
    }
}
