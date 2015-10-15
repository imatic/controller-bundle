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
use Imatic\Bundle\ControllerBundle\Exception\MissingVendorException;
use Imatic\Bundle\ControllerBundle\Controller\Api\Export\ExportApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Import\ImportApi;

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
        return $this->getApi('imatic_controller.api.download', 'download', func_get_args());
    }

    /**
     * @return FormApi
     */
    public function form()
    {
        return $this->getApi('imatic_controller.api.form', 'form', func_get_args());
    }

    /**
     * @return FormApi
     */
    public function namedForm()
    {
        return $this->getApi('imatic_controller.api.form', 'namedForm', func_get_args());
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
     * @return ExportApi
     */
    public function export()
    {
        $this->checkVendor('imatic_controller.api.export', 'imatic/importexport-bundle');

        return $this->getApi('imatic_controller.api.export', 'export', func_get_args());
    }

    /**
     * @return ImportApi
     */
    public function import()
    {
        $this->checkVendor('imatic_controller.api.import', 'imatic/importexport-bundle');

        return $this->getApi('imatic_controller.api.import', 'import', func_get_args());
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

    private function checkVendor($name, $vendor)
    {
        if (!$this->container->has($name)) {
            throw new MissingVendorException($name, $vendor);
        }
    }
}
