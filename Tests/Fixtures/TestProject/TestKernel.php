<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject;

use Genemu\Bundle\FormBundle\GenemuFormBundle;
use Imatic\Bundle\ControllerBundle\ImaticControllerBundle;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\AppImaticControllerBundle;
use Imatic\Bundle\DataBundle\ImaticDataBundle;
use Imatic\Bundle\ImportExportBundle\ImaticImportExportBundle;
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
            new GenemuFormBundle(),
            new WebProfilerBundle(),
            new ImaticControllerBundle(),
            new AppImaticControllerBundle(),
            new ImaticDataBundle(),
            new ImaticImportExportBundle(),
            new SensioFrameworkExtraBundle(),
        ];

        return array_merge($parentBundles, $bundles);
    }
}
