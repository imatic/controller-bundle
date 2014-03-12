<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;

class UserListQuery implements QueryObjectInterface
{
    public function build(EntityManager $em)
    {
        return (new QueryBuilder($em))
            ->from('AppImaticControllerBundle:User', 'u')
            ->select('u');
    }
}
