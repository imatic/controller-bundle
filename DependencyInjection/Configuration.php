<?php

namespace Imatic\Bundle\ControllerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('imatic_controller');

        $rootNode
            ->children()
                ->arrayNode('resources_config')
                    ->children()
                        ->arrayNode('prototype')
                            ->children()
                                ->append($this->getResourceConfigSection())
                                ->append($this->getResourceActionsSection())
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('resources')
                    ->prototype('array')
                        ->children()
                            ->append($this->getResourceConfigSection())
                            ->append($this->getResourceActionsSection())
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    public function getResourceActionsSection()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('actions', 'array');

        $actionGroups = ['list', 'item', 'batch', 'api'];
        $actionTypes = ['list', 'autocomplete' ,'show', 'edit', 'create', 'delete', 'batch'];

        $node
            ->prototype('array')
                ->children()
                    ->append($this->getRouteSection())
                    ->scalarNode('query')->end()
                    ->scalarNode('template')->end()
                    ->enumNode('type')->values($actionTypes)->end()
                    ->enumNode('group')->values($actionGroups)->end()
                    ->scalarNode('controller')->end()
                    ->scalarNode('form')->end()
                    ->arrayNode('form_options')
                        ->prototype('variable')->end()
                    ->end()
                    ->scalarNode('command')->end()
                    ->arrayNode('command_parameters')
                        ->prototype('variable')->end()
                    ->end()
                    ->scalarNode('redirect')->end()
                    ->scalarNode('role')->end()
                    ->scalarNode('target')->end()
                    ->scalarNode('filter')->end()
                    ->booleanNode('data_authorization')->end()
                    ->variableNode('fields')->end()
                    ->arrayNode('extra')
                        ->prototype('variable')->end()
                    ->end()
                ->end();

        return $node;
    }


    public function getResourceConfigSection()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('config', 'array');

        $node
            ->children()
                ->append($this->getRouteSection())
                ->scalarNode('entity')->isRequired()->end()
                ->scalarNode('role')->end()
                ->scalarNode('form')->end()
                ->booleanNode('data_authorization')->defaultFalse()->end()
                ->scalarNode('translation_domain')->end()
                ->scalarNode('name')->isRequired()->end()
                ->variableNode('fields')->end()
                ->arrayNode('query')
                    ->children()
                        ->scalarNode('list')->end()
                        ->scalarNode('item')->end()
                    ->end()
                ->end()
                ->arrayNode('extra')
                    ->prototype('variable')
                ->end()
            ->end();

        return $node;
    }

    public function getRouteSection()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('route', 'array');

        $methods = ['get', 'post', 'put', 'delete'];

        $node
            ->ignoreExtraKeys(false)
                ->children()
                    ->scalarNode('name')->end()
                    ->scalarNode('path')->end()
                    ->arrayNode('methods')
                        ->prototype('enum')->values($methods)->end()
                    ->end()
                ->end()
            ->end();

        return $node;
    }
}
