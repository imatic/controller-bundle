<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Command;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Framework\BundleGuesser;
use Imatic\Bundle\ControllerBundle\Exception\InvalidCommandExecutionException;
use Imatic\Bundle\DataBundle\Data\Command\Command as CommandToExecute;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerRepositoryInterface;

class Command
{
    private $commandName;

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
     * @var BundleGuesser
     */
    private $bundleGuesser;

    public function __construct(
        CommandExecutorInterface $commandExecutor,
        HandlerRepositoryInterface $handlerRepository,
        BundleGuesser $bundleGuesser)
    {
        $this->commandExecutor = $commandExecutor;
        $this->bundleGuesser = $bundleGuesser;
        $this->handlerRepository = $handlerRepository;
    }

    public function setAllowedCommands(array $allowedCommands)
    {
        $this->allowedCommands = $allowedCommands;
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
        $command = new CommandToExecute($this->commandName, $parameters);

        return $this->commandExecutor->execute($command);
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
}
