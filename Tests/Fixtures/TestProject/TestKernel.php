<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject;

use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Imatic\Bundle\ControllerBundle\ImaticControllerBundle;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\AppImaticControllerBundle;
use Imatic\Bundle\DataBundle\ImaticDataBundle;
use Imatic\Testing\Test\TestKernel as BaseTestKernel;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;

class TestKernel extends BaseTestKernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
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
            $bundles[] = new Imatic\Bundle\ImportExportBundle\ImaticImportExportBundle();
            $bundles[] = new JMS\SerializerBundle\JMSSerializerBundle();
            $bundles[] = new Imatic\Bundle\ImportBundle\ImaticImportBundle();
        }

        return \array_merge($parentBundles, $bundles);
    }

    public function getProjectDir()
    {
        return __DIR__;
    }
}
