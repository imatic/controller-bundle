<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareTrait;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\AbstractBatchHandler;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryExecutor;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserGreetBatchHandler extends AbstractBatchHandler implements HandlerInterface, CommandExecutorAwareInterface
{
    use CommandExecutorAwareTrait;

    public function __construct(QueryExecutor $queryExecutor)
    {
        $this->queryExecutor = $queryExecutor;
    }

    protected function handleOne($id)
    {
        return $this->commandExecutor->execute(new Command(UserGreetHandler::class, ['username' => $id]));
    }

    protected function handleAll(DisplayCriteriaInterface $displayCriteria)
    {
        throw new \LogicException('unimplemented');
    }
}
