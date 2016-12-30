<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ObjectManager;

class UserCreateHandler implements HandlerInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param CommandInterface $command
     *
     * @return CommandResultInterface|bool|void
     */
    public function handle(CommandInterface $command)
    {
        $user = $command->getParameter('data');

        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }
}
