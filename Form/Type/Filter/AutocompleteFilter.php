<?php

namespace Imatic\Bundle\ControllerBundle\Form\Type\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule\FilterRuleText;

class AutocompleteFilter extends Filter
{
    public function configure()
    {
        $this->addRule(new FilterRuleText('search', null, [], [FilterOperatorMap::OPERATOR_CONTAINS]));
    }
}