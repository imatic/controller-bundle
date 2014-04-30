<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Command;

class BatchCommandApi extends CommandApi
{
    public function batchCommand(array $allowedCommands)
    {
        $this->command->setAllowedCommands($allowedCommands);

        $request = $this->request->getCurrentRequest();
        $command = null;

        foreach ($request->request->all() as $commandCandidateName => $commandCandidateLabel) {
            if (array_key_exists($commandCandidateName, $allowedCommands)) {
                $command = $allowedCommands[$commandCandidateName];
            }
        }

        if (!$command) {
            $this->response->throwAccessDenied(sprintf('Calling a denied command "%s"', $command));
        }

        $this->command->setCommandName($command);
        $this->command->setCommandParameters(['selected' => $request->request->get('selected', [])]);

        return $this;
    }
}
