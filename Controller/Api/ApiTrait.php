<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Controller\Api\Ajax\AutocompleteApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Command\BatchCommandApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Command\CommandApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Command\ObjectCommandApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Download\DownloadApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Form\FormApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Listing\ListingApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Show\ShowApi;
use Imatic\Bundle\ControllerBundle\Exception\ApiNotFoundException;

/**
 * ApiTrait
 * Use ApiTrait as controller shortcut for API access
 */
trait ApiTrait
{
    /**
     * @return AutocompleteApi
     */
    public function autocomplete()
    {
        return $this->getApi('imatic_controller.api.autocomplete', 'autocomplete', func_get_args());
    }

    /**
     * @return BatchCommandApi
     */
    public function batchCommand()
    {
        return $this->getApi('imatic_controller.api.command.batch', 'batchCommand', func_get_args());
    }

    /**
     * @return CommandApi
     */
    public function command()
    {
        return $this->getApi('imatic_controller.api.command', 'command', func_get_args());
    }

    /**
     * @return ObjectCommandApi
     */
    public function objectCommand()
    {
        return $this->getApi('imatic_controller.api.command.object', 'objectCommand', func_get_args());
    }

    /**
     * @return DownloadApi
     */
    public function download()
    {
        return $this->getApi('', 'download', func_get_args());
    }

    /**
     * @return FormApi
     */
    public function form()
    {
        return $this->getApi('imatic_controller.api.form', 'form', func_get_args());
    }

    /**
     * @return ListingApi
     */
    public function listing()
    {
        return $this->getApi('imatic_controller.api.listing', 'listing', func_get_args());
    }

    /**
     * @return ShowApi
     */
    public function show()
    {
        return $this->getApi('imatic_controller.api.show', 'show', func_get_args());
    }

    /**
     * @param string $id
     * @param string $name
     * @param array $arguments
     * @return Api
     */
    protected function getApi($id, $name, array $arguments = [])
    {
        if (!isset($this->container)) {
            throw new \RuntimeException('$container is not attribute of class "%s"', __CLASS__);
        }

        if (!$this->container->has($id)) {
            throw new ApiNotFoundException($name);
        }

        $api = $this->container->get($id);

        if ($arguments) {
            return call_user_func_array([$api, $name], $arguments);
        }

        return $api;
    }
}
