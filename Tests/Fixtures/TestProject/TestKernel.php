<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject;

use Imatic\Bundle\TestingBundle\Test\TestKernel as BaseTestKernel;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class TestKernel extends BaseTestKernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $parentBundles = parent::registerBundles();

        $bundles = [
            new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),

            new \Imatic\Bundle\ControllerBundle\ImaticControllerBundle(),
            new \Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\AppImaticControllerBundle(),
        ];

        return array_merge($parentBundles, $bundles);
    }
}
