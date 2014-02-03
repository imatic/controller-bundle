<?php

namespace Imatic\Bundle\ControllerBundle\Api;

use Imatic\Bundle\ControllerBundle\Api\Feature\Data;
use Imatic\Bundle\ControllerBundle\Api\Feature\Template;

abstract class CommandApi extends Api
{
    /*
     * form life cycle
     * handle akce
     * redirect
     */

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
