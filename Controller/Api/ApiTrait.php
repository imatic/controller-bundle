<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Controller\Api\Ajax\AutocompleteApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Command\BatchCommandApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Command\CommandApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Command\ObjectCommandApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Download\DownloadApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Export\ExportApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Form\FormApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Import\ImportApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Listing\ListingApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Show\ShowApi;
use Imatic\Bundle\ControllerBundle\Exception\MissingVendorException;

/**
 * ApiTrait
 * Use ApiTrait as controller shortcut for API access.
 */
trait ApiTrait
{
    use GetApiTrait;

    /**
     * @return AutocompleteApi
     */
    protected function autocomplete()
    {
        return $this->getApi('imatic_controller.api.autocomplete', 'autocomplete', \func_get_args());
    }

    /**
     * @return BatchCommandApi
     */
    protected function batchCommand()
    {
        return $this->getApi('imatic_controller.api.command.batch', 'batchCommand', \func_get_args());
    }

    /**
     * @return CommandApi
     */
    protected function command()
    {
        return $this->getApi('imatic_controller.api.command', 'command', \func_get_args());
    }

    /**
     * @return ObjectCommandApi
     */
    protected function objectCommand()
    {
        return $this->getApi('imatic_controller.api.command.object', 'objectCommand', \func_get_args());
    }

    /**
     * @return DownloadApi
     */
    protected function download()
    {
        return $this->getApi('imatic_controller.api.download', 'download', \func_get_args());
    }

    /**
     * @return FormApi
     */
    protected function form()
    {
        return $this->getApi('imatic_controller.api.form', 'form', \func_get_args());
    }

    /**
     * @return FormApi
     */
    protected function namedForm()
    {
        return $this->getApi('imatic_controller.api.form', 'namedForm', \func_get_args());
    }

    /**
     * @return ListingApi
     */
    protected function listing()
    {
        return $this->getApi('imatic_controller.api.listing', 'listing', \func_get_args());
    }

    /**
     * @return ShowApi
     */
    protected function show()
    {
        return $this->getApi('imatic_controller.api.show', 'show', \func_get_args());
    }

    /**
     * @return ExportApi
     */
    protected function export()
    {
        $this->checkVendor('imatic_controller.api.export', 'imatic/importexport-bundle');

        return $this->getApi('imatic_controller.api.export', 'export', \func_get_args());
    }

    /**
     * @return ImportApi
     */
    protected function import()
    {
        $this->checkVendor('imatic_controller.api.import', 'imatic/importexport-bundle');

        return $this->getApi('imatic_controller.api.import', 'import', \func_get_args());
    }

    private function checkVendor($name, $vendor)
    {
        if (!$this->container->has($name)) {
            throw new MissingVendorException($name, $vendor);
        }
    }
}
