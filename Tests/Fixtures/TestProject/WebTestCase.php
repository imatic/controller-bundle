<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Imatic\Testing\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
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
