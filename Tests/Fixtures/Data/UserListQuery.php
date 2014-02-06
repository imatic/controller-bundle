<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\Data;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class UserListQuery implements QueryObjectInterface
{
    public $limit;

    public function __construct($limit)
    {
        $this->limit = $limit;
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
            ->setMaxResults($this->limit);
    }
}
