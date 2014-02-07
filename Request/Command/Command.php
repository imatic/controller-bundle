<?php

namespace Imatic\Bundle\ControllerBundle\Request\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Symfony\Component\HttpFoundation\Request;

class Command
{
    /**
     * @var Request
     */
    private $request;

    public function getActionName($key = 'action.name')
    {
        return $this->request->request->get($key);
    }

    public function getActionParameters($key = 'action.parameters')
    {
        return $this->request->request->get($key);
    }

    public function getIdentity($key = 'identity')
    {
        return $this->request->query->get($key);
    }

    public function getIdentities($key = 'identities')
    {
        return (array) $this->request->request->get($key);
    }
}

class PatchCommand
{
    public function __construct($action, array $parameters)
    {
    }

    public function getAction()
    {
    }

    public function getParameter()
    {
    }

    /**
     * @param  string           $handlerName
     * @return CommandInterface
     */
    public function getCommand($handlerName)
    {
        $parameters = $this->getParameters();
        $parameters['action'] = $this->getAction();

        return new \Imatic\Bundle\DataBundle\Data\Command\Command($handlerName, $parameters);
    }
}
