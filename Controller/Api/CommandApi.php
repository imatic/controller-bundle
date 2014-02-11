<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\Data;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\Template;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorInterface;

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
     * @var CommandExecutorInterface
     */
    protected $commandExecutor;

    public function __construct(CommandExecutorInterface $commandExecutor, Data $data, Template $template)
    {
        $this->data = $data;
        $this->template = $template;
        $this->commandExecutor = $commandExecutor;
    }
}
