<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;

trait CommandTrait
{
    private $commandName;

    private $filterCommandNames;

    /**
     * @var CommandExecutorInterface
     */
    private $commandExecutor;

    public function commandName($commandName)
    {
        $this->commandName = $commandName;

        return $this;
    }

    public function filterCommandNames(array $commandNames)
    {
        $this->filterCommandNames = $commandNames;

        return $this;
    }

    protected function setCommandExecutor(CommandExecutorInterface $commandExecutor)
    {
        $this->commandExecutor = $commandExecutor;
    }

    /**
     * @param  string                 $name
     * @return CommandResultInterface
     */
    protected function executeCommand($name, array $parameters = [])
    {
        if (!$this->commandName) {

        }
        $command = new Command($name, $parameters);

        return $this->commandExecutor->execute($command);
        // pokud neni nastaven commandName, tak musi byt parametr v rozmezi filtrovanych commandu
    }
}
