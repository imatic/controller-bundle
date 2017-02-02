<?php

namespace Imatic\Bundle\ControllerBundle\Resource;

class ResourceConfigurationProcessor
{
    public function process(array $resourcesConfig, array $resources)
    {
        $resources = $this->preProcessResources($resources);
        $resources = $this->mergeActionConfiguration($resourcesConfig['defaults'], $resources);
        $resources = $this->finalizeActionsConfiguration($resources);
        $resources = $this->finalizeConfigActionsConfiguration($resources, $resourcesConfig['actions']);

        return $resources;
    }

    public function preProcessResources(array $resources)
    {
        $resources = $this->fillResourceNames($resources);

        $resources = array_map(function (array $resource) {

            $resource['actions'] = $this->fillActionNames($resource['actions']);
            $resource['actions'] = $this->fillActionTypes($resource['actions']);

            $config = (array)(isset($resource['config']) ? $resource['config'] : []);
            $resource['config'] = $this->preProcessResourceConfig($config, $resource['resource']);

            return $resource;
        }, $resources);

        return $resources;
    }

    public function fillResourceNames(array $resources)
    {
        array_walk($resources, function (&$resource, $key) {
            $resource = (array)$resource;

            $resource['actions'] = array_map(function ($action) use ($key) {
                return array_merge((array)$action, ['resource' => $key]);
            }, $resource['actions']);

            $resource['resource'] = $key;
        });

        return $resources;
    }

    public function fillActionNames(array $actions)
    {
        array_walk($actions, function (&$action, $key) {
            $action = (array)$action;

            $action['action'] = $key;
        });

        return $actions;
    }

    public function fillActionTypes(array $actions)
    {
        array_walk($actions, function (&$action, $key) {
            $action = (array)$action;
            if (empty($action['type'])) {
                $action = array_merge($action, ['type' => $key]);
            }
        });

        return $actions;
    }

    public function preProcessResourceConfig(array $config, $resourceName)
    {
        // Route
        if (empty($config['route'])) {
            $config['route'] = [];
        }
        if (empty($config['route']['path'])) {
            $config['route']['path'] = '/' . str_replace(['_', '-', '.'], '/', $resourceName);
        }

        // Translation domain
        if (empty($config['translation_domain'])) {
            $config['translation_domain'] = str_replace(['.', '_', '-'], '', ucwords($resourceName, '\.\_\-'));
        }

        // Resource name
        if (empty($config['name'])) {
            $config['name'] = $resourceName;
        }

        // Role name
        if (empty($config['role'])) {
            $config['role'] = null;
        }

        return $config;
    }

    public function mergeActionConfiguration(array $defaultConfig, array $resources)
    {
        return array_map(function (array $resource) use ($defaultConfig) {
            $resource['actions'] = array_map(function (array $action) use ($defaultConfig) {
                $type = $action['type'];
                $defaultResourceConfig = $defaultConfig[$type];

                return array_replace_recursive($defaultResourceConfig, $action);
            }, $resource['actions']);


            return $resource;
        }, $resources);
    }

    public function finalizeActionsConfiguration(array $resources)
    {
        return array_map(function (array $resource) {
            $resource['actions'] = array_map(function (array $action) use ($resource) {
                return $this->finalizeActionConfiguration($action, $resource['config']);
            }, $resource['actions']);

            return $resource;
        }, $resources);
    }

    public function finalizeActionConfiguration(array $action, array $config)
    {
        // Route - action route prepend with resource route
        $action['route']['path'] = $config['route']['path'] . ($action['route']['path'] === '/' ? '' : $action['route']['path']);
        $action['route']['name'] = sprintf('%s_%s', $action['resource'], $action['action']);

        // Query - if action query is not defined, use resource query (item or collection by action type)
        $queryKey = $action['collection'] ? 'collection' : 'item';
        if (empty($action['query']) && !empty($config['query'][$queryKey])) {
            $action['query'] = $config['query'][$queryKey];
        }

        // Entity - copy entity class to action
        $action['entity'] = $config['entity'];

        // Fields - if action fields is not defined, use resource fields
        if (empty($action['fields']) && !empty($config['fields'])) {
            $action['fields'] = $config['fields'];
        }

        // Role
        if (empty($action['role'])) {
            $action['role'] = null;
        }

        return $action;
    }

    public function finalizeConfigActionsConfiguration(array $resources, $defaultActions)
    {
        return array_map(function ($resource) use ($defaultActions) {
            return $this->finalizeConfigActionConfiguration($resource, $defaultActions);
        }, $resources);
    }

    public function finalizeConfigActionConfiguration(array $resource, array $defaultActions)
    {
        $resourceActionsGroups = (array)(isset($resource['config']['actions']) ? $resource['config']['actions'] : []);
        $resourceActionsGroups = array_replace_recursive($defaultActions, $resourceActionsGroups);

        foreach ($resourceActionsGroups as $resourceActionsGroupName => $resourceActions) {
            foreach ($resourceActions as $actionName => $action) {
                if (false === $action) {
                    unset($resourceActions[$actionName]);
                    continue;
                }

                if (empty($action['route']) && !empty($resource['actions'][$actionName]['route']['name'])) {
                    $resourceActions[$actionName]['route'] = $resource['actions'][$actionName]['route']['name'];
                }

                if (empty($action['role'])) {
                    $resourceActions[$actionName]['role'] = null;
                }

                if (empty($action['label'])) {
                    $resourceActions[$actionName]['label'] = ucfirst($actionName);
                }

                if (isset($resourceActions[$actionName]['route']) && is_array($resourceActions[$actionName]['route'])) {
                    $resourceActions[$actionName]['route'] = $resourceActions[$actionName]['route']['name'];
                }
                if (empty($action['route']) && !empty($resource['actions'][$actionName]['route']['name'])) {
                    $resourceActions[$actionName]['route'] = $resource['actions'][$actionName]['route']['name'];
                }
            }

            // Nested actions
            foreach ($resourceActions as $actionName => $action) {
                if (!empty($action['nested']) && array_key_exists($action['nested'], $resourceActions)) {
                    $parent = $action['nested'];
                    if (!isset($resourceActions[$parent]['nested'])) {
                        $resourceActions[$parent]['nested'] = [];
                    }

                    unset($action['nested'], $resourceActions[$actionName]);
                    $resourceActions[$parent]['nested'][$actionName] = $action;
                }
            }

            $resourceActionsGroups[$resourceActionsGroupName] = $resourceActions;
        }

        $resource['config']['actions'] = $resourceActionsGroups;

        return $resource;
    }
}