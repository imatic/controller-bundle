<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;

class UserQuery implements QueryObjectInterface, SingleResultQueryObjectInterface
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function build(EntityManagerInterface $em): QueryBuilder
    {
        return (new QueryBuilder($em))
            ->from(User::class, 'u')
            ->select('u')
            ->where('u.id = :id')
            ->setParameter('id', $this->id);
    }
}
