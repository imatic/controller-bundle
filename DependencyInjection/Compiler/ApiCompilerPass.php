<?php
namespace Imatic\Bundle\ControllerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

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
            foreach ($tagAttributes as $attributes) {
                $apiRepositoryDef->addMethodCall('add', [
                    $attributes['alias'],
                    new Reference($id),
                ]);
            }
        }
    }
}
