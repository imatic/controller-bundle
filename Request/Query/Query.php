<?php

namespace Imatic\Bundle\ControllerBundle\Request;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterInterface;
use Symfony\Component\HttpFoundation\Request;

class Query
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Pager
     */
    protected $pager;

    /**
     * @var Sorter
     */
    protected $sorter;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var RequestParser
     */
    protected $requestParser;

    /**
     * @param  Request       $request
     * @return RequestHelper
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->requestParser = new RequestParser($this->request);
    }

    /**
     * @return PagerInterface
     */
    public function getPager()
    {
        if (!$this->pager) {
            $this->pager = $this->requestParser->getPager();
        }

        return $this->pager;
    }

    /**
     * @return SorterInterface
     */
    public function getSorter()
    {
        if (!$this->sorter) {
            $this->sorter = $this->requestParser->getSorter();
        }

        return $this->sorter;
    }

    /**
     * @return FilterInterface
     */
    public function getFilter()
    {
        if (!$this->filter) {
            $this->filter = $this->requestParser->getFilter();
        }

        return $this->filter;
    }
}
