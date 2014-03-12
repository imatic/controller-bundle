<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ObjectManager;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;

class UserDeleteHandler implements HandlerInterface
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var QueryExecutorInterface
     */
    private $queryExecutor;

    public function __construct(ObjectManager $objectManager, QueryExecutorInterface $queryExecutor)
    {
        $this->objectManager = $objectManager;
        $this->queryExecutor = $queryExecutor;
    }

    /**
     * @param  CommandInterface $command
     * @return CommandResultInterface|bool|void
     */
    public function handle(CommandInterface $command)
    {
        $user = $command->getParameter('user');
        if (!($user instanceof User)) {
            $user = $this->queryExecutor->execute(new UserQuery($user));
        }

        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }
}
