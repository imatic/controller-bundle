<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

trait FormTrait
{
    protected $formName;

    protected $formEmptyValue;

    public function formEmptyValue($value)
    {
        $this->setFormEmptyValue($value);
    }

    protected function setFormEmptyValue($value)
    {
        $this->formEmptyValue = $value;
    }

    protected function getFormEmptyValue()
    {
        return $this->formEmptyValue;
    }

    protected function getFormName()
    {
        return $this->formName;
    }

    protected function setFormName($name)
    {
        $this->formName = $name;
    }
}
