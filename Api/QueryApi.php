<?php

namespace Imatic\Bundle\ControllerBundle\Api;

use Imatic\Bundle\ControllerBundle\Api\Feature\Data;
use Imatic\Bundle\ControllerBundle\Api\Feature\DataTrait;
use Imatic\Bundle\ControllerBundle\Api\Feature\Template;
use Imatic\Bundle\ControllerBundle\Api\Feature\TemplateTrait;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
