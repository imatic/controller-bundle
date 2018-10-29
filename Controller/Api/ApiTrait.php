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
        return $this->getApi(AutocompleteApi::class, 'autocomplete', \func_get_args());
    }

    /**
     * @return BatchCommandApi
     */
    protected function batchCommand()
    {
        return $this->getApi(BatchCommandApi::class, 'batchCommand', \func_get_args());
    }

    /**
     * @return CommandApi
     */
    protected function command()
    {
        return $this->getApi(CommandApi::class, 'command', \func_get_args());
    }

    /**
     * @return ObjectCommandApi
     */
    protected function objectCommand()
    {
        return $this->getApi(ObjectCommandApi::class, 'objectCommand', \func_get_args());
    }

    /**
     * @return DownloadApi
     */
    protected function download()
    {
        return $this->getApi(DownloadApi::class, 'download', \func_get_args());
    }

    /**
     * @return FormApi
     */
    protected function form()
    {
        return $this->getApi(FormApi::class, 'form', \func_get_args());
    }

    /**
     * @return FormApi
     */
    protected function namedForm()
    {
        return $this->getApi(FormApi::class, 'namedForm', \func_get_args());
    }

    /**
     * @return ListingApi
     */
    protected function listing()
    {
        return $this->getApi(ListingApi::class, 'listing', \func_get_args());
    }

    /**
     * @return ShowApi
     */
    protected function show()
    {
        return $this->getApi(ShowApi::class, 'show', \func_get_args());
    }

    /**
     * @return ExportApi
     */
    protected function export()
    {
        $this->checkVendor(ExportApi::class, 'imatic/importexport-bundle');

        return $this->getApi(ExportApi::class, 'export', \func_get_args());
    }

    /**
     * @return ImportApi
     */
    protected function import()
    {
        $this->checkVendor(ImportApi::class, 'imatic/importexport-bundle');

        return $this->getApi(ImportApi::class, 'import', \func_get_args());
    }

    private function checkVendor($name, $vendor)
    {
        if (!$this->container->has($name)) {
            throw new MissingVendorException($name, $vendor);
        }
    }
}
