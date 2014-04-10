<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Download;

trait DownloadApiTrait
{
    /**
     * @return DownloadApi
     */
    public function download()
    {
        return $this->getApi('download', func_get_args());
    }
}
