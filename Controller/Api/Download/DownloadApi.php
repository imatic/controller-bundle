<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Controller\Api\Download;

use Imatic\Bundle\ControllerBundle\Controller\Api\Query\ItemQueryApi;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use SplFileInfo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadApi extends ItemQueryApi
{
    protected $fileObjectInterface = 'Imatic\Bundle\DocumentBundle\File\FileObjectInterface';

    protected $fileInfoInterface = 'Imatic\Bundle\DocumentBundle\File\FileInfoInterface';

    public function download($object, $forceDownload = true, $name = null)
    {
        $file = null;

        if ($object instanceof SplFileInfo) {
            $file = $object;

            if (!$name) {
                if ($object instanceof UploadedFile) {
                    $name = $object->getClientOriginalName();
                } else {
                    $name = $object->getFilename();
                }
            }
        } else {
            if ($object instanceof SingleResultQueryObjectInterface) {
                $object = $this->data->query('object', $object);
            }

            if (\interface_exists($this->fileObjectInterface) && $object instanceof $this->fileObjectInterface) {
                $file = $object->getFile();

                if (!$name) {
                    if (
                        $object instanceof $this->fileInfoInterface ||
                        \is_callable([$object, 'getFileName'])
                    ) {
                        $name = $object->getFilename();
                    } else {
                        $name = $file->getFilename();
                    }
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
        $this->security->checkDataAuthorization($this->data->all());

        $response = new BinaryFileResponse($this->data->get('file'), 200, [], false, 'inline');
        $response->setContentDisposition(
            $this->data->get('forceDownload') ? ResponseHeaderBag::DISPOSITION_ATTACHMENT : ResponseHeaderBag::DISPOSITION_INLINE,
            $this->data->get('name'),
            \iconv('UTF-8', 'ASCII//TRANSLIT', $this->data->get('name'))
        );

        return $response;
    }
}
