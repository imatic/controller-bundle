<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\Command;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\Data;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\Template;

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

    /**
     * @var Command
     */
    protected $command;

    public function __construct(Command $command, Data $data, Template $template)
    {
        $this->data = $data;
        $this->template = $template;
        $this->command = $command;
    }
}
