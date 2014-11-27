<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Export;

use Imatic\Bundle\ControllerBundle\Controller\Api\Api;
use Imatic\Bundle\ControllerBundle\Controller\Api\Download\DownloadApi;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Imatic\Bundle\ImportExportBundle\Export\Exporter;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Imatic\Bundle\ImportExportBundle\Util\File;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ExportApi extends Api
{
    /** @var Exporter */
    protected $exporter;

    /** @var FilterFactory */
    protected $filterFactory;

    /** @var DownloadApi */
    protected $downloadApi;

    /** @var UploadedFile */
    protected $outputFile;

    /** @var string */
    protected $filter;

    /** @var SorterInterface */
    protected $sorter;

    public function __construct(
        RequestFeature $request,
        ResponseFeature $response,
        Exporter $exporter,
        DownloadApi $downloadApi,
        FilterFactory $filterFactory
    ) {
        parent::__construct($request, $response);

        $this->exporter = $exporter;
        $this->downloadApi = $downloadApi;
        $this->filterFactory = $filterFactory;
    }

    public function export(QueryObjectInterface $queryObject, $format, $name, array $options = [])
    {
        if (!array_key_exists('displayCriteria', $options)) {
            $dcOptions = [];
            if ($this->filter) {
                $dcOptions['filter'] = $this->filterFactory->create($this->filter);
            }
            if ($this->sorter) {
                $dcOptions['sorter'] = $this->sorter;
            }

            $options['displayCriteria'] = $this->request->getDisplayCriteria($dcOptions);
        }

        $result = $this->exporter->export($queryObject, $format, $options);
        $tmpUri = File::createTempFile('csv');
        file_put_contents($tmpUri, $result);

        $this->outputFile = new UploadedFile($tmpUri, $name);

        return $this;
    }

    public function filter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function defaultSort(array $sort)
    {
        $this->sorter = $sort;

        return $this;
    }

    public function getResponse()
    {
        $this->downloadApi->download($this->outputFile);

        return $this->downloadApi->getResponse();
    }
}
