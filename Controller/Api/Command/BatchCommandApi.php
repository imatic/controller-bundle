<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Command;

class BatchCommandApi extends CommandApi
{
    /**
     * @param array|string $allowedCommands
     *
     * @return BatchCommandApi
     */
    public function batchCommand($allowedCommands)
    {
        $request = $this->request->getCurrentRequest();

        $command = null;
        if (is_array($allowedCommands)) {
            $this->command->setAllowedCommands($allowedCommands);

            foreach ($request->request->all() as $commandCandidateName => $commandCandidateLabel) {
                if (array_key_exists($commandCandidateName, $allowedCommands)) {
                    $command = $allowedCommands[$commandCandidateName];
                }
            }

            if (!$command) {
                $this->response->throwAccessDenied(sprintf('Calling a denied command "%s"', $command));
            }
        }

        if (!$command) {
            $command = $allowedCommands;
        }

        $this->command->setCommandName($command);
        $this->command->setCommandParameters([
            'selected' => $request->request->get('selected', []),
            'selectedAll' => $request->request->get('selectedAll', false),
            'query' => $request->get('query', []),
        ]);

        return $this;
    }
}
