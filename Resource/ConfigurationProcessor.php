<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Resource;

use Imatic\Bundle\ControllerBundle\Resource\Config\Resource;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceAction;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceConfig;

class ConfigurationProcessor
{
    public function process(array $resourcesConfig, array $resources)
    {
        return $this->processResources($resources, $resourcesConfig['prototype'] ?? []);
    }

    public function processResources(array $resources, array $prototype)
    {
        $resources = static::arrayMap(function (array $resource, $resourceName) use ($prototype) {
            return $this->processResource(\array_merge($resource, ['name' => $resourceName]), $prototype);
        }, $resources);

        $resources = $this->configureActionLinks($resources);

        return $resources;
    }

    public function processResource(array $resource, array $prototype)
    {
        // Configure resource (fill default values)
        $resource['config'] = $this->configureResourceConfig($resource['config'], $resource['name']);
        $resource['actions'] = $this->configureResourceActions($resource['actions']);

        // Merge resource with prototype
        $resource = $this->mergeResource($resource, $prototype);

        // Finalize resource configuration after merge
        $resource['actions'] = $this->finalizeResourceActions($resource['actions'], $resource['config'], $resource['name']);

        // Convert actions into objects
        $resource['actions'] = static::arrayMap(function ($action) {
            return new ResourceAction($action);
        }, $resource['actions']);

        return new Resource($resource['actions'], new ResourceConfig($resource['config']), $resource['name']);
    }

    private function configureResourceConfig(array $config, $resourceName)
    {
        // Route
        if (empty($config['route'])) {
            $config['route'] = [];
        }
        if (empty($config['route']['path'])) {
            $config['route']['path'] = '/' . \str_replace(['_', '-', '.'], '/', $resourceName);
        }

        // Translation domain
        if (empty($config['translation_domain'])) {
            $config['translation_domain'] = \str_replace(['.', '_', '-'], '', \ucwords($resourceName, '\.\_\-'));
        }

        // Role name
        if (empty($config['role'])) {
            $config['role'] = null;
        }

        return $config;
    }

    private function configureResourceActions(array $actions)
    {
        $actions = \array_filter($actions, function ($action) {
            return false !== $action;
        });

        return self::arrayMap(function ($action, $actionName) {
            $action = (array) $action;

            // Target action (config is provided by remote action)
            if (!empty($action['target'])) {
                return $action;
            }

            // Actions type
            if (empty($action['type'])) {
                $action['type'] = $actionName;
            }

            // Actions name
            if (empty($action['name'])) {
                $action['name'] = $actionName;
            }

            return $action;
        }, $actions);
    }

    private function mergeResource(array $resource, $prototype)
    {
        $resource['actions'] = static::arrayMap(function (array $action) use ($prototype) {
            // Target action (config is provided by remote action)
            if (!empty($action['target'])) {
                return $action;
            }

            $type = $action['type'];
            $defaultResourceConfig = isset($prototype['actions'][$type]) ? $prototype['actions'][$type] : [];

            return \array_replace_recursive($defaultResourceConfig, $action);
        }, $resource['actions']);

        return $resource;
    }

    private function finalizeResourceActions(array $actions, array $config, $resourceName)
    {
        $actions = static::arrayMap(function (array $action) use ($config, $resourceName) {
            // Target action (config is provided by remote action)
            if (!empty($action['target'])) {
                return $action;
            }

            // Route - action route prepend with resource route
            $action['route']['path'] = $config['route']['path'] . ($action['route']['path'] === '/' ? '' : $action['route']['path']);
            $action['route']['name'] = \sprintf('%s_%s', $resourceName, $action['name']);

            // Query - if action query is not defined, use resource query (by action group)
            if (empty($action['query']) && !empty($config['query'][$action['group']])) {
                $action['query'] = $config['query'][$action['group']];
            }

            // Entity - copy entity class to action
            $action['entity'] = $config['entity'];

            // Fields - if action fields is not defined, use resource fields
            if (empty($action['fields']) && !empty($config['fields'])) {
                $action['fields'] = $config['fields'];
            }

            // Form - if action form is not defined, use resource form
            if (empty($action['form']) && !empty($config['form']) && \in_array($action['type'], ['create', 'edit'], true)) {
                $action['form'] = $config['form'];
            }

            // Role
            if (empty($action['role'])) {
                $action['role'] = null;
            }

            // Data authorization
            if (!isset($action['data_authorization']) && isset($config['data_authorization'])) {
                $action['data_authorization'] = $config['data_authorization'];
            }

            return $action;
        }, $actions);

        // Step 2 - fill based on previously filled values
        $actions = static::arrayMap(function (array $action) use ($actions) {
            // Redirect
            if (!empty($action['redirect']) && !empty($actions[$action['redirect']]['route']['name'])) {
                $action['redirect'] = $actions[$action['redirect']]['route']['name'];
            }

            return $action;
        }, $actions);

        return $actions;
    }

    private function configureActionLinks(array $resources)
    {
        return static::arrayMap(function (Resource $resource, $resourceName) use ($resources) {
            $actions = static::arrayMap(function (ResourceAction $action, $actionName) use ($resources, $resourceName) {
                if (!empty($action['target'])) {
                    list($targetResourceName, $targetActionName) = \explode(':', $action['target']);

                    if (empty($resources[$targetResourceName]) || !$resources[$targetResourceName]->hasAction($targetActionName)) {
                        throw new \RuntimeException(\sprintf(
                            'Cannot link action "%s:%s to "%s:%s", target action not found',
                            $resourceName,
                            $actionName,
                            $targetResourceName,
                            $targetActionName
                        ));
                    }

                    $action = $resources[$targetResourceName]->getAction($targetActionName)->link();
                }

                return $action;
            }, $resource->getActions());

            $resource->setActions($actions);

            return $resource;
        }, $resources);
    }

    /**
     * - array keys are available in callback
     * - array keys have not been changed
     */
    public static function arrayMap(callable $callback, array $array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = \call_user_func($callback, $value, $key);
        }

        return $array;
    }
}
