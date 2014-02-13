<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Form;

use Symfony\Component\Form\FormFactoryInterface;

class FormFeature
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $emptyValue;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
        $this->options = [];
    }

    public function getEmptyValue()
    {
        return $this->emptyValue;
    }

    public function setEmptyValue($value)
    {
        $this->emptyValue = $value;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function addOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm()
    {
        return $this->formFactory->create($this->name, $this->emptyValue, $this->options);
    }
}
