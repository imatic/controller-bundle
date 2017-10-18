<?php
namespace Imatic\Bundle\ControllerBundle\Form\Type\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\TextRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;

class AutocompleteFilter extends Filter
{
    public function configure()
    {
        $this->add((new TextRule('search'))->setOperator(FilterOperatorMap::OPERATOR_CONTAINS));
    }
}
