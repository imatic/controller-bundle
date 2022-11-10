<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserListQuery;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareTrait;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\RecordIterator;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\RecordIteratorArgs;

final class UserGreetBatchHandler implements HandlerInterface, CommandExecutorAwareInterface
{
    use CommandExecutorAwareTrait;

    private RecordIterator $iterator;

    public function __construct(RecordIterator $iterator)
    {
        $this->iterator = $iterator;
    }

    public function handle(CommandInterface $command)
    {
        $count = 0;

        $iterator = new RecordIteratorArgs($command, new UserListQuery(), function () use (&$count) {
            ++$count;

            return CommandResult::success();
        });

        $this->iterator->each($iterator);

        return CommandResult::success("Hromadná akce byla provedena pro $count");
    }
}
