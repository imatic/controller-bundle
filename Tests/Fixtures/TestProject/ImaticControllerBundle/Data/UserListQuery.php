<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;

class UserListQuery implements QueryObjectInterface, FilterableQueryObjectInterface
{
    public function build(EntityManager $em): QueryBuilder
    {
        return (new QueryBuilder($em))
            ->from('AppImaticControllerBundle:User', 'u')
            ->select('u');
    }

    public function getFilterMap()
    {
        return [
            'search' => 'u.name',
        ];
    }
}
