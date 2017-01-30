<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

class ResourceConfigurationProcessor
{
    public function process(array $resourcesConfig, array $resources)
    {
        $resources = $this->preProcessResources($resources);
        $resources = $this->mergeActionConfiguration($resourcesConfig['defaults'], $resources);
        $resources = $this->finalizeActionsConfiguration($resources);

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

        return $action;
    }
}