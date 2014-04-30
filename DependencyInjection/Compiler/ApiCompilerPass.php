<?php
namespace Imatic\Bundle\ControllerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ApiCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $apiServices = $container->findTaggedServiceIds('imatic_controller.api');
        $apiRepositoryDef = $container->findDefinition('imatic_controller.api_repository');

        foreach ($apiServices as $id => $tagAttributes) {
            $container->findDefinition($id)
                ->setScope('prototype')
                ->setLazy(true)
            ;

            foreach ($tagAttributes as $attributes) {
                $apiRepositoryDef->addMethodCall('add', [
                    $attributes['alias'],
                    $id,
                ]);
            }
        }
    }
}
