<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Command\Message;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserGreetBatchHandler implements HandlerInterface
{
    public function handle(CommandInterface $command)
    {
        $usernames = $command->getParameter('selected');

        $messages = [];
        foreach ($usernames as $username) {
            $messages[] = new Message('', sprintf('Hello %s!', $username));
        }

        return new CommandResult(true, $messages);
    }
}
