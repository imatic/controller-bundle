<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Imatic\Testing\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    protected function setUp()
    {
        static::createClient()->getContainer();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return self::$container->get(EntityManagerInterface::class);
    }

    /**
     * @return string
     */
    protected static function getKernelClass()
    {
        return 'Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\TestKernel';
    }
}
