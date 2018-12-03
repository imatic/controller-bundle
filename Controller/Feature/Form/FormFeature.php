<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Form;

use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormFeature
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

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

    public function addOption($name, $value, $overrideExisting = true)
    {
        if (!isset($this->options[$name]) || $overrideExisting) {
            $this->options[$name] = $value;
        }
    }

    public function getOption($name, $default = null)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        return $default;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @param FormInterface $form
     *
     * @return string|null
     */
    public function getSubmittedName(FormInterface $form)
    {
        foreach ($form as $field) {
            if ($field instanceof ClickableInterface && $field->isClicked()) {
                return $field->getName();
            }
        }

        return null;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->formFactory->createNamed($this->name, $this->type, $this->emptyValue, $this->options);
    }
}
