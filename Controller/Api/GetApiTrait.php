<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Exception\ApiNotFoundException;

trait GetApiTrait
{
    /**
     * @return Api
     */
    protected function getApi(string $id, string $name, array $arguments = [])
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
