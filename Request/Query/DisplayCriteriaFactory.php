<?php
namespace Imatic\Bundle\ControllerBundle\Request\Query;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteria;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Sorter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @todo Podle typu dat (json/xml...) pridat servanty
 * ($this->servants['json']->getCriteria($displayCriteriaData))
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
     * @return DisplayCriteria
     */
    public function getCriteria($componentId = null)
    {
        return new DisplayCriteria(
            $this->createPager(
                $this->getAttribute('page', null, $componentId),
                $this->getAttribute('limit', null, $componentId)
            ),
            $this->createSorter(
                $this->getAttribute('sorter', [], $componentId)
            ),
            $this->createFilter(
                $this->getAttribute('filter', [], $componentId)
            )
        );
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @param string|null $component
     * @return mixed
     */
    protected function getAttribute($name, $default = null, $component = null)
    {
        $request = $this->requestStack->getCurrentRequest();

        $path = $name;
        if ($component) {
            $path = $component . '[' . $name . ']';
        }

        return $request->query->get($path, $default, true);
    }

    /**
     * @param int|null $page
     * @param int|null $limit
     * @return Pager
     */
    protected function createPager($page, $limit)
    {
        return $this->pagerFactory->createPager($page, $limit);
    }

    /**
     * @param array $filterData ['fieldName' => ['value' => 'fieldValue', 'operator' => 'operator']]
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
