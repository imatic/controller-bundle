<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\DoctrineORMObjectManager;

class UserActivateHandler implements HandlerInterface
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
        /** @var User $user */
        $user = $command->getParameter('object');
        $user->setActive(!$user->isActive());

        $this->objectManager->flush();
    }
}
