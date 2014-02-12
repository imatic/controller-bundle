<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Framework;

use Symfony\Component\HttpKernel\KernelInterface;

class BundleGuesser
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    // todo: pres parent - muze jit o proxy tridu!!!

    public function guess($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
        $reflectionClass = new \ReflectionClass($class);
        $bundles = $this->kernel->getBundles();

        do {
            $namespace = $reflectionClass->getNamespaceName();
            foreach ($bundles as $bundle) {
                if (0 === strpos($namespace, $bundle->getNamespace())) {
                    return $bundle;
                }
            }
            $reflectionClass = $reflectionClass->getParentClass();
        } while ($reflectionClass);

        return null;
    }
}