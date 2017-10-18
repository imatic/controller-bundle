<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Api\Listing;

use Imatic\Bundle\ControllerBundle\Controller\Api\Query\QueryApi;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeature;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\DisplayCriteriaReader;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Response;

class ListingApi extends QueryApi
{
    /** @var string|null */
    protected $filter;
    /** @var array|null */
    protected $sort;
    /** @var array|null */
    protected $pager;
    /** @var string|null */
    protected $componentId;
    /** @var QueryObjectInterface */
    protected $queryObject;
    /** @var DisplayCriteriaInterface|null */
    protected $displayCriteria;
    /** @var DisplayCriteriaReader */
    protected $displayCriteriaReader;
    /** @var FilterFactory */
    protected $filterFactory;
    /** @var bool */
    protected $enablePersistentDisplayCriteria = false;
    /** @var bool */
    protected $dataCalculated = false;
    /** @var bool */
    protected $disableCountQuery = false;

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

    /**
     * @param string $filter
     *
     * @return static
     */
    public function filter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @param array $sort
     *
     * @return static
     */
    public function defaultSort(array $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @param int|null $limit
     *
     * @return static
     */
    public function defaultLimit($limit)
    {
        $this->pager[DisplayCriteriaReader::LIMIT] = $limit;

        return $this;
    }

    public function enablePersistentDisplayCriteria()
    {
        if (null === $this->componentId) {
            $this->componentId = $this->generateComponentId();
        }

        $this->enablePersistentDisplayCriteria = true;

        return $this;
    }

    public function disablePersistentDisplayCriteria()
    {
        $this->enablePersistentDisplayCriteria = false;

        return $this;
    }

    public function disableCountQuery()
    {
        $this->disableCountQuery = true;

        return $this;
    }

    public function enableCountQuery()
    {
        $this->disableCountQuery = false;

        return $this;
    }

    /**
     * @param string|null $componentId
     *
     * @return static
     */
    public function componentId($componentId)
    {
        $this->componentId = $componentId;

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

        $this->template->addTemplateVariable('component_id', $this->getComponentId());

        if (!$this->disableCountQuery) {
            $this->displayCriteria->getPager()->setTotal($this->data->get('items_count'));
        }

        $this->template->addTemplateVariable('display_criteria', $this->displayCriteria);

        $this->template->addTemplateVariables($this->data->all());

        return new Response($this->template->render());
    }

    /**
     * @return string
     */
    private function getComponentId()
    {
        return $this->componentId;
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
            if ($this->pager) {
                $dcOptions['pager'] = $this->pager;
            }
            $dcOptions['componentId'] = $this->getComponentId();

            $this->displayCriteria = $this->request->getDisplayCriteria(
                $dcOptions,
                $this->enablePersistentDisplayCriteria
            );
        }

        if ($this->disableCountQuery) {
            $this->data->query('items', $this->queryObject, $this->displayCriteria);
        } else {
            $this->data->queryAndCount('items', 'items_count', $this->queryObject, $this->displayCriteria);
        }

        $query = [];
        if (\is_string($this->filter)) { // this is here to avoid crash of unprepaired projects
            $filterKey = $this->displayCriteriaReader->attributeName(DisplayCriteriaReader::FILTER);
            $filterValue = $this->displayCriteriaReader->readAttribute(DisplayCriteriaReader::FILTER, null, $this->getComponentId(), $this->enablePersistentDisplayCriteria);
            $query = [
                $filterKey => $filterValue,
            ];
            $query['filter_type'] = $this->filter;
        }

        $this->data->set('query', \json_encode($query));
        $this->dataCalculated = true;
    }

    /**
     * @return string
     */
    private function generateComponentId()
    {
        $route = $this->request->getCurrentRoute();
        $routeParams = $this->request->getCurrentRouteParams();

        $componentId = $route;

        if (!empty($routeParams)) {
            $componentId .= \sprintf('_%x', \crc32(\http_build_query($routeParams)));
        }

        return $componentId;
    }
}
