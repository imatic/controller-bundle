<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Request;

use Imatic\Bundle\ControllerBundle\Request\Query\DisplayCriteriaFactory;
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
     * @param string|null $componentId
     * @return DisplayCriteriaInterface
     */
    public function getDisplayCriteria($componentId = null)
    {
        return $this->displayCriteriaFactory->getCriteria($componentId);
    }
}
