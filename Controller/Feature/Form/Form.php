<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Form;

use Symfony\Component\Form\FormFactoryInterface;

class Form
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
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getEmptyValue()
    {
        return $this->emptyValue;
    }

    public function setEmptyValue($value)
    {
        $this->emptyValue = $value;
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
        return $this->formFactory->create($this->name, $this->emptyValue);
    }
}
