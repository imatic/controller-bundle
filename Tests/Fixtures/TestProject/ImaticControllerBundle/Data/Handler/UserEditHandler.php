<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\DoctrineORMObjectManager;

class UserEditHandler implements HandlerInterface
{

    /**
     * @var DoctrineORMObjectManager
     */
    private $objectManager;

    public function __construct(DoctrineORMObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param  CommandInterface $command
     * @return CommandResultInterface|bool|void
     */
    public function handle(CommandInterface $command)
    {
        $this->objectManager->flush();
    }
}
