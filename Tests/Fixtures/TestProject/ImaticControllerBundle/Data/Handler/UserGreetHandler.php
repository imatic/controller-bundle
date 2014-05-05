<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Command\Message;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserGreetHandler implements HandlerInterface
{
    public function handle(CommandInterface $command)
    {
        $message = sprintf('Hello %s!', $command->getParameter('username'));

        return new CommandResult(true, [
            new Message('', $message)
        ]);
    }
}
