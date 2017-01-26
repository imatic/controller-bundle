<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Download;

use Imatic\Bundle\ControllerBundle\Controller\Api\Query\QueryApi;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use SplFileInfo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadApi extends QueryApi
{
    protected $fileObjectInterface = 'Imatic\Bundle\DocumentBundle\File\FileObjectInterface';

    protected $fileInfoInterface = 'Imatic\Bundle\DocumentBundle\File\FileInfoInterface';

    public function download($object, $forceDownload = true, $name = null)
    {
        $file = null;

        if ($object instanceof SplFileInfo) {
            $file = $object;

            if ($object instanceof UploadedFile) {
                $name = $object->getClientOriginalName();
            } else {
                $name = $object->getFilename();
            }
        } else {
            if ($object instanceof SingleResultQueryObjectInterface) {
                $object = $this->data->query('object', $object);
            }

            if (interface_exists($this->fileObjectInterface) && $object instanceof $this->fileObjectInterface) {
                $file = $object->getFile();
                $name = $file->getFilename();

                if (
                    $object instanceof $this->fileInfoInterface ||
                    is_callable([$object, 'getFileName'])
                ) {
                    $name = $object->getFilename();
                }
            }
        }

        $this->response->throwNotFoundUnless($file);
        $this->data->set('file', $file);
        $this->data->set('name', $name);
        $this->data->set('forceDownload', $forceDownload);

        return $this;
    }

    public function getResponse()
    {
        $response = new BinaryFileResponse($this->data->get('file'), 200, [], false, 'inline');
        $response->setContentDisposition(
            $this->data->get('forceDownload') ? ResponseHeaderBag::DISPOSITION_ATTACHMENT : ResponseHeaderBag::DISPOSITION_INLINE,
            $this->data->get('name'),
            iconv('UTF-8', 'ASCII//TRANSLIT', $this->data->get('name'))
        );

        return $response;
    }
}
