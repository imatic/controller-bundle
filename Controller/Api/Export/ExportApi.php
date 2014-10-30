<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Export;

use Imatic\Bundle\ControllerBundle\Controller\Api\Api;
use Imatic\Bundle\ControllerBundle\Controller\Api\Download\DownloadApi;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Imatic\Bundle\ImportExportBundle\Export\Exporter;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ExportApi extends Api
{
    /** @var Exporter */
    protected $exporter;

    /** @var DownloadApi */
    protected $downloadApi;

    /** @var UploadedFile */
    protected $outputFile;

    public function __construct(
        RequestFeature $request,
        ResponseFeature $response,
        Exporter $exporter,
        DownloadApi $downloadApi
    ) {
        parent::__construct($request, $response);

        $this->exporter = $exporter;
        $this->downloadApi = $downloadApi;
    }

    public function export(QueryObjectInterface $queryObject, $format, $name, array $options = [])
    {
        $result = $this->exporter->export($queryObject, $format, $options);
        $tmp = tmpfile();
        $tmpMetadata = stream_get_meta_data($tmp);
        $tmpUri = $tmpMetadata['uri'];
        file_put_contents($tmpUri, $result);

        $this->outputFile = new UploadedFile($tmpUri, $name);

        return $this;
    }

    public function getResponse()
    {
        $this->downloadApi->download($this->outputFile);

        return $this->downloadApi->getResponse();
    }
}
