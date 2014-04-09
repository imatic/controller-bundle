<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect;

use Imatic\Bundle\ControllerBundle\Exception\InvalidRedirectException;
use Imatic\Bundle\ControllerBundle\Exception\InvalidRedirectParameterException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectFeature
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var array
     */
    private $redirects;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(UrlGeneratorInterface $urlGenerator, RequestStack $request)
    {
        $this->urlGenerator = $urlGenerator;
        $this->redirects = [];
        $this->requestStack = $request;
    }

    public function setSuccessRedirect($routeName, $parameters = null)
    {
        $this->setRedirect('success', $routeName, $parameters);
    }

    public function setErrorRedirect($routeName, $parameters = null)
    {
        $this->setRedirect('error', $routeName, $parameters);
    }

    public function getSuccessRedirectUrl($parameters = [])
    {
        return $this->getRedirectUrl('success', $parameters);
    }

    public function getErrorRedirectUrl($parameters = [])
    {
        return $this->getRedirectUrl('error', $parameters);
    }

    public function getRedirectUrl($name, $parameters = [])
    {
        if (!array_key_exists($name, $this->redirects)) {
            throw new InvalidRedirectException($name);
        }

        if ($this->redirects[$name]['parameters'] instanceof \Closure) {
            $parameters = call_user_func_array($this->redirects[$name]['parameters'], $parameters);
        } else {
            $parameters = array_merge($this->redirects[$name]['parameters'], $parameters);
        }

        if ('@current' === $this->redirects[$name]['route']) {
            $url = $this->requestStack->getCurrentRequest()->getUri();
        } else {
            $url = $this->urlGenerator->generate($this->redirects[$name]['route'], $parameters);
        }

        return $url;
    }

    protected function setRedirect($name, $routeName, $parameters = null)
    {
        $parameters = (null === $parameters ? [] : $parameters);
        if (!is_array($parameters) && !($parameters instanceof \Closure)) {
            throw new InvalidRedirectParameterException($parameters);
        }
        $this->redirects[$name] = ['route' => $routeName, 'parameters' => $parameters];
    }
}
