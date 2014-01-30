<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

trait CommandTrait
{
    private $commandName;

    private $filterCommandNames;

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

    protected function setCommandExecutor()
    {

    }

    /**
     * @param string $name
     * @return CommandResultInterface
     */
    protected function executeCommand($name)
    {
        if (!$this->commandName) {

        }

        // pokud neni nastaven commandName, tak musi byt parametr v rozmezi filtrovanych commandu
    }
}
