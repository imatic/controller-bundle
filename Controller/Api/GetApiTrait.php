<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Exception\ApiNotFoundException;

trait GetApiTrait
{
    /**
     * @param string $id
     * @param string $name
     * @param array $arguments
     *
     * @return Api
     */
    protected function getApi($id, $name, array $arguments = [])
    {
        if (!isset($this->container)) {
            throw new \RuntimeException(\sprintf('$container is not attribute of class "%s"', __CLASS__));
        }

        if (!$this->container->has($id)) {
            throw new ApiNotFoundException($name);
        }

        $api = $this->container->get($id);

        if ($arguments) {
            return \call_user_func_array([$api, $name], $arguments);
        }

        return $api;
    }
}
