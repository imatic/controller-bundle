<?php declare(strict_types=1);
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

    public static function getSubscribedServices()
    {
        $services = parent::getSubscribedServices();

        $services['autocompleteApi'] = AutocompleteApi::class;
        $services['batchCommandApi'] = BatchCommandApi::class;
        $services['commandApi'] = CommandApi::class;
        $services['objectCommandApi'] = ObjectCommandApi::class;
        $services['downloadApi'] = DownloadApi::class;
        $services['formApi'] = FormApi::class;
        $services['listingApi'] = ListingApi::class;
        $services['showApi'] = ShowApi::class;
        $services['exportApi'] = '?' . ExportApi::class;
        $services['importApi'] = '?' . ImportApi::class;

        return $services;
    }

    /**
     * @return Api|AutocompleteApi
     */
    protected function autocomplete()
    {
        return $this->getApi('autocompleteApi', 'autocomplete', \func_get_args());
    }

    /**
     * @return Api|BatchCommandApi
     */
    protected function batchCommand()
    {
        return $this->getApi('batchCommandApi', 'batchCommand', \func_get_args());
    }

    /**
     * @return Api|CommandApi
     */
    protected function command()
    {
        return $this->getApi('commandApi', 'command', \func_get_args());
    }

    /**
     * @return Api|ObjectCommandApi
     */
    protected function objectCommand()
    {
        return $this->getApi('objectCommandApi', 'objectCommand', \func_get_args());
    }

    /**
     * @return Api|DownloadApi
     */
    protected function download()
    {
        return $this->getApi('downloadApi', 'download', \func_get_args());
    }

    /**
     * @return Api|FormApi
     */
    protected function form()
    {
        return $this->getApi('formApi', 'form', \func_get_args());
    }

    /**
     * @return Api|FormApi
     */
    protected function namedForm()
    {
        return $this->getApi('formApi', 'namedForm', \func_get_args());
    }

    /**
     * @return Api|ListingApi
     */
    protected function listing()
    {
        return $this->getApi('listingApi', 'listing', \func_get_args());
    }

    /**
     * @return Api|ShowApi
     */
    protected function show()
    {
        return $this->getApi('showApi', 'show', \func_get_args());
    }

    /**
     * @return Api|ExportApi
     */
    protected function export()
    {
        $this->checkVendor('exportApi', 'imatic/importexport-bundle');

        return $this->getApi('exportApi', 'export', \func_get_args());
    }

    /**
     * @return Api|ImportApi
     */
    protected function import()
    {
        $this->checkVendor('importApi', 'imatic/importexport-bundle');

        return $this->getApi('importApi', 'import', \func_get_args());
    }

    private function checkVendor($name, $vendor)
    {
        if (!$this->container->has($name)) {
            throw new MissingVendorException($name, $vendor);
        }
    }
}
