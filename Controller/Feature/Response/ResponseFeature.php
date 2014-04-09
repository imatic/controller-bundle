<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Response;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ResponseFeature
{
    public function throwAccessDenied($message = 'Access denied')
    {
        throw new AccessDeniedException($message);
    }

    public function throwNotFound($message = null)
    {
        if (!$message) {
            $message = 'Resource not found';
        }
        throw new NotFoundHttpException($message);
    }

    public function throwNotFoundUnless($object = null, $message = null)
    {
        if (is_null($object)) {
            $this->throwNotFound($message);
        }
    }

    public function throwError($message = 'Server error')
    {
        throw new NotFoundHttpException($message);
    }

    public function createRedirect($url)
    {
        return new RedirectResponse($url);
    }
}
