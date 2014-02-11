<?php

namespace Imatic\Bundle\ControllerBundle;

use Imatic\Bundle\ControllerBundle\DependencyInjection\Compiler\ApiCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ImaticControllerBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ApiCompilerPass());
    }
}
