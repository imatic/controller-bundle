<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('imatic_controller');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('resources_config')
                    ->children()
                        ->arrayNode('prototype')
                            ->info('Default values for resource config. Default values for actions are indexed by action type.')
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
        $builder = new TreeBuilder('actions');
        $node = $builder->getRootNode();

        $actionGroups = ['list', 'item', 'batch', 'api'];
        $actionTypes = ['list', 'autocomplete', 'show', 'edit', 'create', 'delete', 'batch'];

        $node
            ->prototype('array')
                ->children()
                    ->append($this->getRouteSection())
                    ->scalarNode('query')
                        ->info('Query object for the action.')
                    ->end()
                    ->scalarNode('template')
                        ->info('Template used for rendering the action.')
                    ->end()
                    ->enumNode('type')
                        ->info('Type of the action (merges prototype action with name of the "type" with this action).')
                        ->values($actionTypes)
                    ->end()
                    ->enumNode('group')
                        ->info('Group of the action. In case "query" is not specified, the one from "config.query.$group is used".')
                        ->values($actionGroups)
                    ->end()
                    ->scalarNode('controller')
                        ->info('Specification of controller action to execute.')
                    ->end()
                    ->scalarNode('form')->end()
                    ->arrayNode('form_options')
                        ->prototype('variable')->end()
                    ->end()
                    ->scalarNode('command')->end()
                    ->arrayNode('command_parameters')
                        ->prototype('variable')->end()
                    ->end()
                    ->scalarNode('redirect')->end()
                    ->scalarNode('role')
                        ->info('Role required to perform the action.')
                    ->end()
                    ->scalarNode('target')
                        ->info('This action refers to another action. Format is: "resource:action"')
                    ->end()
                    ->scalarNode('filter')->end()
                    ->booleanNode('data_authorization')->end()
                    ->variableNode('fields')
                        ->info('Fields of the resource passed into templates in imatic/view-bundle.')
                    ->end()
                    ->arrayNode('extra')
                        ->info('Extra configuration which can be used e.g. in templates.')
                        ->prototype('variable')->end()
                    ->end()
                ->end();

        return $node;
    }

    public function getResourceConfigSection()
    {
        $builder = new TreeBuilder('config');
        $node = $builder->getRootNode();

        $node
            ->info('Generic config usable by all actions.')
            ->children()
                ->append($this->getRouteSection())
                ->scalarNode('entity')->isRequired()->end()
                ->scalarNode('role')
                    ->info('Role to check before working with resource.')
                ->end()
                ->scalarNode('form')
                    ->info('Form for the resource')
                ->end()
                ->booleanNode('data_authorization')
                    ->info('Flag if roles should be checked.')
                    ->defaultFalse()
                ->end()
                ->scalarNode('translation_domain')->end()
                ->scalarNode('name')->isRequired()->end()
                ->variableNode('fields')
                    ->info('Fields of the resource passed into templates in imatic/view-bundle.')
                ->end()
                ->arrayNode('query')
                    ->children()
                        ->scalarNode('list')
                            ->info('Query object for action of type "list".')
                        ->end()
                        ->scalarNode('item')
                            ->info('Query object for actions using single resource object. It expects first argument to be id of the object and second class of the object.')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('extra')
                    ->info('Extra configuration which can be used e.g. in templates.')
                    ->prototype('variable')
                ->end()
            ->end();

        return $node;
    }

    public function getRouteSection()
    {
        $builder = new TreeBuilder('route');
        $node = $builder->getRootNode();

        $methods = ['get', 'post', 'put', 'delete'];

        $node
            ->info('Specification of generated route.')
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
