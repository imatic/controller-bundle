<?php

namespace Imatic\Bundle\ControllerBundle\DependencyInjection;

use Imatic\Bundle\ControllerBundle\Controller\Resource\ResourceConfigurationProcessor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ImaticControllerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // Merge configuration files
        $config = [];
        foreach ($configs as $subConfig) {
            $config = array_replace_recursive($config, $subConfig);
        }

        // Process and validate configuration
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, [$config]);

        // Resources configuration
        $this->processResourcesConfiguration($config['resources_config'], $config['resources'], $container);

        // Load services
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $this->loadImportConfiguration($loader);
    }

    private function processResourcesConfiguration(array $resourcesConfig, array $resources, ContainerBuilder $container)
    {
        $resourceConfigurationProcessor = new ResourceConfigurationProcessor();
        $config = $resourceConfigurationProcessor->process($resourcesConfig, $resources);

        $container->setParameter('imatic_controller.resource.config', $config);
    }

    private function loadImportConfiguration(Loader\YamlFileLoader $loader)
    {
        if (class_exists('Imatic\Bundle\ImportExportBundle\ImaticImportExportBundle')) {
            $loader->load('import_export.yml');
        }
    }
}
