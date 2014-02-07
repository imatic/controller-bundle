<?php
namespace Imatic\Bundle\ControllerBundle\Request\Query;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteria;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Sorter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @todo Podle typu dat (json/xml...) pridat servanty
 * ($this->servants['json']->getCriteria($displayCriteriaData))
 *
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DisplayCriteriaFactory
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var PagerFactory
     */
    protected $pagerFactory;

    protected $defaultData = [
        'filter' => [],
        'sorter' => [],
        'pager' => [],
    ];

    /**
     * @param RequestStack $requestStack
     * @param PagerFactory $pagerFactory
     */
    public function __construct(RequestStack $requestStack, PagerFactory $pagerFactory)
    {
        $this->requestStack = $requestStack;
        $this->pagerFactory = $pagerFactory;
    }

    /**
     * @param string|null $componentId
     *
     * @return DisplayCriteria
     */
    public function getCriteria($componentId = null)
    {
        $request = $this->requestStack->getCurrentRequest();
        $displayCriteriaData = $this->getDisplayCriteriaDataFromRequest($request, $componentId);

        return new DisplayCriteria(
            $this->createPager($displayCriteriaData['pager']),
            $this->createSorter($displayCriteriaData['sorter']),
            $this->createFilter($displayCriteriaData['filter'])
        );
    }

    /**
     * @param Request     $request
     * @param string|null $componentId
     *
     * @return array
     */
    protected function getDisplayCriteriaDataFromRequest(Request $request, $componentId = null)
    {
        if ($componentId !== null) {
            return $displayCriteriaData = array_merge($this->defaultData, $request->query->get($componentId, []));
        }

        return $displayCriteriaData = [
            'filter' => $request->query->get('filter', $this->defaultData['filter']),
            'sorter' => $request->query->get('sorter', $this->defaultData['sorter']),
            'pager' => $request->query->get('pager', $this->defaultData['pager']),
        ];
    }

    /**
     * @param array $pagerData ['page' => page_number, 'per_page' => results_per_page_number]
     *
     * @return Pager
     */
    protected function createPager(array $pagerData)
    {
        $data = array_merge([
            'page' => 1,
            'limit' => null
        ], $pagerData);

        return $this->pagerFactory->createPager($data['page'], $data['limit']);
    }

    /**
     * @param array $filterData ['fieldName' => ['value' => 'fieldValue', 'operator' => 'operator']]
     *
     * @return Filter
     */
    protected function createFilter(array $filterData)
    {
        $filterRules = [];
        foreach ($filterData as $fieldName => $filterRuleData) {
            $filterRules[] = new FilterRule($fieldName, $filterRuleData['value'], $filterRuleData['operator']);
        }

        return new Filter($filterRules);
    }

    /**
     * @param array $sorterData ['fieldName' => '<asc|desc>']
     *
     * @return Sorter
     */
    protected function createSorter(array $sorterData)
    {
        $sorterRules = [];
        foreach ($sorterData as $fieldName => $direction) {
            $sorterRules[] = new SorterRule($fieldName, $direction);
        }

        return new Sorter($sorterRules);
    }
}
