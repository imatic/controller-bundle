<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\CommandFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeature;

abstract class CommandApi extends Api
{
    /*
     * form life cycle
     * handle akce
     * redirect
     */

    /**
     * @var DataFeature
     */
    protected $data;

    /**
     * @var TemplateFeature
     */
    protected $template;

    /**
     * @var CommandFeature
     */
    protected $command;

    public function __construct(CommandFeature $command, DataFeature $data, TemplateFeature $template)
    {
        $this->data = $data;
        $this->template = $template;
        $this->command = $command;
    }
}
