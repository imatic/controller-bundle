<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject;

use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Imatic\Bundle\ControllerBundle\ImaticControllerBundle;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\AppImaticControllerBundle;
use Imatic\Bundle\DataBundle\ImaticDataBundle;
use Imatic\Bundle\ImportBundle\ImaticImportBundle;
use Imatic\Bundle\ImportExportBundle\ImaticImportExportBundle;
use Imatic\Testing\Test\TestKernel as BaseTestKernel;
use JMS\SerializerBundle\JMSSerializerBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;

class TestKernel extends BaseTestKernel
{
    public function registerBundles(): iterable
    {
        $parentBundles = parent::registerBundles();

        $bundles = [
            new WebProfilerBundle(),
            new ImaticDataBundle(),
            new ImaticControllerBundle(),
            new AppImaticControllerBundle(),
            new DoctrineFixturesBundle(),
        ];

        if (\class_exists('Imatic\Bundle\ImportExportBundle\ImaticImportExportBundle')) {
            $bundles[] = new ImaticImportExportBundle();
            $bundles[] = new JMSSerializerBundle();
            $bundles[] = new ImaticImportBundle();
        }

        return \array_merge($parentBundles, $bundles);
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}
