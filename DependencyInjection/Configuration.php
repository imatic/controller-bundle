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

        $actionTypes = ['list', 'autocomplete' ,'show', 'edit', 'create', 'delete', 'batch'];

        $rootNode
            ->children()
                ->arrayNode('resources_config')
                    ->children()
                        ->arrayNode('defaults')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('template')->end()
                                    ->scalarNode('type')->end()
                                    ->booleanNode('collection')->end()
                                    ->append($this->getRouteConfiguration())
                                    ->scalarNode('controller')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('required')
                            ->prototype('array')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('resources')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('config')
                                ->children()
                                    ->append($this->getRouteConfiguration())
                                    ->arrayNode('query')
                                        ->children()
                                            ->scalarNode('collection')->end()
                                            ->scalarNode('item')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('actions')
                                ->prototype('array')
                                    ->children()
                                        ->append($this->getRouteConfiguration())
                                        ->enumNode('type')->values($actionTypes)->end()
                                        ->scalarNode('query')->end()
                                        ->scalarNode('template')->end()
                                        ->scalarNode('form')->end()
                                        ->scalarNode('command')->end()
                                        ->scalarNode('redirect')->end()
                                        ->scalarNode('controller')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    public function getRouteConfiguration()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('route', 'array');

        $node
            ->ignoreExtraKeys(false)
                ->children()
                    ->scalarNode('path')->end()
                    ->arrayNode('method')
                        ->prototype('enum')->values(['get', 'post', 'put', 'delete'])->end()
                    ->end()
                ->end()
            ->end();

        return $node;
    }
}
