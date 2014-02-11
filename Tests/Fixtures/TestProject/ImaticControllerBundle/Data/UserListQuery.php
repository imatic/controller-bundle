<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\DoctrineORMQueryObjectInterface;

class UserListQuery implements DoctrineORMQueryObjectInterface
{
    public $limit;

    public function __construct($limit)
    {
        $this->limit = $limit;
    }

    public function build(EntityManager $em)
    {
        return (new QueryBuilder($em))
            ->from('AppImaticControllerBundle:User', 'u')
            ->select('u')
            ->setMaxResults($this->limit);
    }
}
