<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\Data;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class UserQuery implements QueryObjectInterface
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param  ObjectManager $om
     * @return mixed         Instance of QueryBuilder, concrete type depends on used persistence backend
     */
    public function build(ObjectManager $om)
    {
        return (new QueryBuilder($om))
            ->from('User', 'u')
            ->select('u')
            ->where('id = :id')
            ->setParameter('id', $this->id);
    }
}
