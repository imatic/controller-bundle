<?php

namespace Imatic\Bundle\ControllerBundle\Request;

use Imatic\Bundle\ControllerBundle\Data\Filter\Filter;
use Imatic\Bundle\ControllerBundle\Data\Pager\Pager;
use Imatic\Bundle\ControllerBundle\Data\Sorter\Sorter;
use Imatic\Bundle\ControllerBundle\Data\Sorter\SorterRule;
use Symfony\Component\HttpFoundation\Request;

class RequestParser
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     * @return RequestParser
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Imatic\Bundle\ControllerBundle\Data\Pager\Pager
     */
    public function getPager()
    {
        return new Pager($this->request->query->getInt('page'), $this->request->query->getInt('limit'));
    }

    /**
     * @return \Imatic\Bundle\ControllerBundle\Data\Sorter\Sorter
     */
    public function getSorter()
    {
        $sorter = new Sorter();
        $query = $this->request->query;

        if ('json' === $this->getType()) {
            $sortString = $query->get('sort');
            if ($sortString && $sortArray = json_decode($sortString, true)) {
                foreach ($sortArray as $sortArrayItem) {
                    $sorter->addSorterRule(new SorterRule($sortArrayItem['property'], $sortArrayItem['direction']));
                }
            }
        } else {
            if ($query->has('sort')) {
                $sorter->addSorterRule(new SorterRule($query->get('sort'), $query->get('sortdir')));
            }
        }

        return $sorter;
    }

    /**
     * @return \Imatic\Bundle\ControllerBundle\Data\Filter\Filter
     */
    public function getFilter()
    {

    }

    /**
     * @return string
     */
    public function getType()
    {
        $requestFormat = $this->request->getRequestFormat();
        return $requestFormat;
    }
}