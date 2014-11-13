<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Request;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestFeature
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var DisplayCriteriaFactory
     */
    private $displayCriteriaFactory;

    public function __construct(RequestStack $requestStack, DisplayCriteriaFactory $displayCriteriaFactory)
    {
        $this->requestStack = $requestStack;
        $this->displayCriteriaFactory = $displayCriteriaFactory;
    }

    /**
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    public function getCurrentRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @return string
     */
    public function getCurrentRoute()
    {
        return $this->getCurrentRequest()->get('_route');
    }

    /**
     * @return string
     */
    public function getCurrentUri()
    {
        return $this->getCurrentRequest()->getUri();
    }

    /**
     * @param array $options
     * @return DisplayCriteriaInterface
     */
    public function getDisplayCriteria(array $options = [])
    {
        return $this->displayCriteriaFactory->createCriteria($options);
    }
}
