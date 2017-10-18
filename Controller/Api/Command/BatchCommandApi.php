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

        if (\is_array($allowedCommands)) {
            $this->command->setAllowedCommands($allowedCommands);
            $this->command->setCommandNames(\array_keys($request->request->all()));
        }

        if (!$this->command->getCommandName()) {
            $this->command->setCommandName($allowedCommands);
        }

        $this->command->addCommandParameters([
            'selected' => $request->request->get('selected', []),
            'selectedAll' => $request->request->get('selectedAll', false),
            'query' => $request->get('query', []),
        ]);

        return $this;
    }
}
