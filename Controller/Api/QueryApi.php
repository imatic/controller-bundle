<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\Data;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\Template;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateTrait;

abstract class QueryApi extends Api
{
    use TemplateTrait;
    use DataTrait;

    /**
     * @var Data
     */
    protected $data;

    /**
     * @var Template
     */
    protected $template;

    public function __construct(Data $data, Template $template)
    {
        $this->data = $data;
        $this->template = $template;
    }
}
