<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject;

use Imatic\Bundle\ControllerBundle\ImaticControllerBundle;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\AppImaticControllerBundle;
use Imatic\Bundle\DataBundle\ImaticDataBundle;
use Imatic\Bundle\TestingBundle\Test\TestKernel as BaseTestKernel;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
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
            new SensioFrameworkExtraBundle(),
            new ImaticControllerBundle(),
            new AppImaticControllerBundle(),
        ];

        if (class_exists('Imatic\Bundle\ImportExportBundle\ImaticImportExportBundle')) {
            $bundles[] = new Imatic\Bundle\ImportExportBundle\ImaticImportExportBundle();
            $bundles[] = new JMS\SerializerBundle\JMSSerializerBundle();
            $bundles[] = new Imatic\Bundle\ImportBundle\ImaticImportBundle();
        }

        return array_merge($parentBundles, $bundles);
    }
}
