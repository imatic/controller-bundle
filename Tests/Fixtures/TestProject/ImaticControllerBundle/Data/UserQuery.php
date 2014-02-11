<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\DoctrineORMQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;

class UserQuery implements DoctrineORMQueryObjectInterface, SingleResultQueryObjectInterface
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function build(EntityManager $em)
    {
        return (new QueryBuilder($em))
            ->from('AppImaticControllerBundle:User', 'u')
            ->select('u')
            ->where('u.id = :id')
            ->setParameter('id', $this->id);
    }
}
