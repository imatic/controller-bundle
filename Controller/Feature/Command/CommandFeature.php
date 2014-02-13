<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Command;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Framework\BundleGuesserFeature;
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

    /**
     * @var BundleGuesserFeature
     */
    private $bundleGuesser;

    public function __construct(
        CommandExecutorInterface $commandExecutor,
        HandlerRepositoryInterface $handlerRepository,
        BundleGuesserFeature $bundleGuesser)
    {
        $this->commandExecutor = $commandExecutor;
        $this->bundleGuesser = $bundleGuesser;
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

    public function getBundleName()
    {
        $bundle = $this->bundleGuesser->guess($this->handlerRepository->getHandler(new CommandToExecute($this->commandName)));

        // todo: nemusi vratit bundle, ale null:(

        return $bundle->getName();
    }

    public function isValid()
    {
        $valid = true;

        if (!$this->commandName) {
            $valid = false;
        }

        if (!empty($this->allowedCommands) && !in_array($this->commandName, $this->allowedCommands)) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * @param array $parameters
     * @throws InvalidCommandExecutionException
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
