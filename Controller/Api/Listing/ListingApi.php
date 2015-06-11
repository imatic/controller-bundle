<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Listing;

use Imatic\Bundle\ControllerBundle\Controller\Api\Query\QueryApi;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeature;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Response;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\DisplayCriteriaReader;

class ListingApi extends QueryApi
{
    protected $filter;

    protected $sort;

    protected $defaultSorter;

    protected $queryObject;

    protected $displayCriteria;

    protected $displayCriteriaReader;

    /** @var FilterFactory */
    protected $filterFactory;

    /** @var bool */
    protected $dataCalculated = false;

    public function __construct(
        RequestFeature $request,
        ResponseFeature $response,
        TemplateFeature $template,
        DataFeature $data,
        DisplayCriteriaReader $displayCriteriaReader,
        FilterFactory $filterFactory
    ) {
        parent::__construct($request, $response, $template, $data);
        $this->filterFactory = $filterFactory;
        $this->displayCriteriaReader = $displayCriteriaReader;
    }

    public function listing(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        $this->displayCriteria = $displayCriteria;
        $this->queryObject = $queryObject;

        return $this;
    }

    public function filter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function defaultSort(array $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    public function getValue($name)
    {
        $this->calculateData();

        return parent::getValue($name);
    }

    public function getResponse()
    {
        $this->calculateData();

        $this->displayCriteria->getPager()->setTotal($this->data->get('items_count'));
        $this->template->addTemplateVariable('display_criteria', $this->displayCriteria);

        $this->template->addTemplateVariables($this->data->all());

        return new Response($this->template->render());
    }

    private function calculateData()
    {
        if ($this->dataCalculated) {
            return;
        }

        if (null === $this->displayCriteria) {
            $dcOptions = [];
            if ($this->filter) {
                $dcOptions['filter'] = $this->filterFactory->create($this->filter);
            }
            if ($this->sort) {
                $dcOptions['sorter'] = $this->sort;
            }
            $this->displayCriteria = $this->request->getDisplayCriteria($dcOptions);
        }

        $this->data->query('items', $this->queryObject, $this->displayCriteria);
        $this->data->count('items_count', $this->queryObject, $this->displayCriteria);

        $query = [];
        if (is_string($this->filter)) { // this is here to avoid crash of unprepaired projects
            $filterKey = $this->displayCriteriaReader->attributeName(DisplayCriteriaReader::FILTER);
            $filterValue = $this->displayCriteriaReader->readAttribute(DisplayCriteriaReader::FILTER);
            $query = [
                $filterKey => $filterValue,
            ];
            $query['filter_type'] = $this->filter;
        }

        $this->data->set('query', json_encode($query));
        $this->dataCalculated = true;
    }
}
