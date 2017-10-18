<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Command;

use Imatic\Bundle\ControllerBundle\Exception\InvalidCommandExecutionException;
use Imatic\Bundle\DataBundle\Data\Command\Command as CommandToExecute;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerRepositoryInterface;

class CommandFeature
{
    private $commandName;

    /**
     * @var array
     */
    private $commandParameters;

    private $allowedCommands;

    /**
     * @var CommandExecutorInterface
     */
    private $commandExecutor;

    /**
     * @var HandlerRepositoryInterface
     */
    private $handlerRepository;

    public function __construct(
        CommandExecutorInterface $commandExecutor,
        HandlerRepositoryInterface $handlerRepository
    ) {
        $this->commandExecutor = $commandExecutor;
        $this->handlerRepository = $handlerRepository;
        $this->commandParameters = [];
    }

    public function setAllowedCommands(array $allowedCommands)
    {
        $this->allowedCommands = $allowedCommands;
    }

    public function setCommandParameters(array $commandParameters)
    {
        $this->commandParameters = $commandParameters;
    }

    public function addCommandParameters(array $commandParameters)
    {
        $this->commandParameters = array_merge($this->commandParameters, $commandParameters);
    }

    public function addCommandParameter($name, $object)
    {
        $this->commandParameters[$name] = $object;
    }

    public function getCommandName()
    {
        return $this->commandName;
    }

    public function setCommandName($commandName)
    {
        $this->commandName = $commandName;
    }

    public function setCommandNames(array $names)
    {
        $allowedCommandsNames = array_map(function ($command) {
            return is_array($command) ? $command['command'] : $command;
        }, $this->allowedCommands);

        foreach ($names as $name) {
            if (array_key_exists($name, $allowedCommandsNames)) {
                $this->setCommandName($allowedCommandsNames[$name]);

                if (isset($this->allowedCommands[$name]['command_parameters'])) {
                    $this->addCommandParameters($this->allowedCommands[$name]['command_parameters']);
                }
            }
        }
    }

    public function isValid()
    {
        if (!$this->commandName) {
            return false;
        }

        return true;
    }

    /**
     * @param array $parameters
     *
     * @throws InvalidCommandExecutionException
     *
     * @return CommandResultInterface
     */
    public function execute(array $parameters = [])
    {
        if (!$this->isValid()) {
            throw new InvalidCommandExecutionException($this->commandName);
        }

        $parameters = array_merge($this->commandParameters, $parameters);
        $command = new CommandToExecute($this->commandName, $parameters);

        return $this->commandExecutor->execute($command);
    }
}
