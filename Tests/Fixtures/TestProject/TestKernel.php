<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject;

use Imatic\Bundle\ControllerBundle\ImaticControllerBundle;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\AppImaticControllerBundle;
use Imatic\Bundle\DataBundle\ImaticDataBundle;
use Imatic\Bundle\TestingBundle\Test\TestKernel as BaseTestKernel;
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
            new ImaticControllerBundle(),
            new AppImaticControllerBundle(),
            new ImaticDataBundle(),
        ];

        return array_merge($parentBundles, $bundles);
    }
}
