<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Command;

/**
 * @property CommandFeature $command
 */
trait CommandFeatureTrait
{
    public function allowedCommandNames(array $commandNames)
    {
        $this->command->setAllowedCommands($commandNames);

        return $this;
    }

    public function commandName($commandName)
    {
        $this->command->setCommandName($commandName);

        return $this;
    }

    public function addCommandParameters(array $parameters)
    {
        $this->command->addCommandParameters($parameters);

        return $this;
    }

    public function commandParameters(array $parameters)
    {
        $this->command->setCommandParameters($parameters);

        return $this;
    }
}
