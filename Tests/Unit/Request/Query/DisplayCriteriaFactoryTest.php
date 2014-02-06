<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Unit\Request\Query;

use Imatic\Bundle\ControllerBundle\Request\Query\DisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DisplayCriteriaFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $displayCriteriaFactory;
    private $currentRequest;

    protected function setUp()
    {
        $requestStack = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');

        $requestStack
            ->expects($this->any())
            ->method('getCurrentRequest')
            ->will($this->returnCallback(function () {
                return $this->currentRequest;
            }))
        ;

        $pagerFactory = $this->getMock('Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerFactory');

        $pagerFactory
            ->expects($this->any())
            ->method('createPager')
            ->will($this->returnCallback(function ($page, $limit) {
                return new Pager($page, $limit);
            }))
        ;

        $this->displayCriteriaFactory = new DisplayCriteriaFactory($requestStack, $pagerFactory);
    }

    public function testGetCriteriaShouldReturnDefaultValuesIfComponentIsNotInsideRequest()
    {
        $this->currentRequest = new Request();

        $displayCriteria = $this->displayCriteriaFactory->getCriteria('nonExistingComponent');

        $this->assertEquals(1, $displayCriteria->getPager()->getPage());
        $this->assertCount(0, $displayCriteria->getFilter());
        $this->assertCount(0, $displayCriteria->getSorter());
    }

    public function testGetCriteriaShouldReturnValuesFromRequest()
    {
        $this->currentRequest = new Request([
            'componentFromRequest' => [
                'filter' => [
                    'name' => [
                        'value' => 'Lee',
                        'operator' => 'NOT LIKE',
                    ],
                ],
                'sorter' => [
                    'name' => 'ASC',
                ],
                'pager' => [
                    'page' => 31,
                    'limit' => 123,
                ],
            ],
        ]);

        $displayCriteria = $this->displayCriteriaFactory->getCriteria('componentFromRequest');

        $pager = $displayCriteria->getPager();
        $this->assertEquals(31, $pager->getPage());
        $this->assertEquals(123, $pager->getLimit());

        $sorter = $displayCriteria->getSorter();
        $this->assertCount(1, $sorter);
        $this->assertEquals('ASC', $sorter->getDirection('name'));

        $filter = $displayCriteria->getFilter();
        $this->assertCount(1, $filter);
        $this->assertEquals('name', $filter->getAt(0)->getColumn());
        $this->assertEquals('Lee', $filter->getAt(0)->getValue());
        $this->assertEquals('NOT LIKE', $filter->getAt(0)->getOperator());
    }

    public function testGetCriteriaShouldReturnValuesFromRequestRootIfComponentIdWasNotSpecified()
    {
        $this->currentRequest = new Request([
            'filter' => [
                'name' => [
                    'value' => 'Lee',
                    'operator' => 'NOT LIKE',
                ],
            ],
            'sorter' => [
                'name' => 'ASC',
            ],
            'pager' => [
                'page' => 31,
                'limit' => 123,
            ],
        ]);

        $displayCriteria = $this->displayCriteriaFactory->getCriteria();

        $pager = $displayCriteria->getPager();
        $this->assertEquals(31, $pager->getPage());
        $this->assertEquals(123, $pager->getLimit());

        $sorter = $displayCriteria->getSorter();
        $this->assertCount(1, $sorter);
        $this->assertEquals('ASC', $sorter->getDirection('name'));

        $filter = $displayCriteria->getFilter();
        $this->assertCount(1, $filter);
        $this->assertEquals('name', $filter->getAt(0)->getColumn());
        $this->assertEquals('Lee', $filter->getAt(0)->getValue());
        $this->assertEquals('NOT LIKE', $filter->getAt(0)->getOperator());
    }
}
