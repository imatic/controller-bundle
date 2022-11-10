<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SelectableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ResultQueryObjectInterface;

class UserListQuery implements QueryObjectInterface, FilterableQueryObjectInterface, SelectableQueryObjectInterface, ResultQueryObjectInterface
{
    public function build(EntityManager $em): QueryBuilder
    {
        return (new QueryBuilder($em))->from(User::class, 'u')->select('u');
    }

    public function getFilterMap(): array
    {
        return [
            'id' => 'u.id',
            'search' => 'u.name',
        ];
    }

    public function getIdentifierFilterKey(): string
    {
        return 'id';
    }
}
