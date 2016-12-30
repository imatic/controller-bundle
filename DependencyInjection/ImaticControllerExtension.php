<?php

namespace Imatic\Bundle\ControllerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}.
 */
class ImaticControllerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        if (class_exists('Imatic\Bundle\ImportExportBundle\ImaticImportExportBundle')) {
            $loader->load('import_export.yml');
        }
    }
}
