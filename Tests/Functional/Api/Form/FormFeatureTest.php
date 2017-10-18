<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Form;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Form\FormFeature;
use Symfony\Component\Form\FormFactoryInterface;

class FormFeatureTest extends \PHPUnit_Framework_TestCase
{
    public function testOptions()
    {
        /** @var FormFactoryInterface $formFactory */
        $formFactory = $this
            ->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')
            ->getMock();

        $feature = new FormFeature($formFactory);

        $this->assertNull($feature->getOption('o1'));
        $this->assertEquals('v', $feature->getOption('o1', 'v'));

        $feature->addOption('o1', 'v1');
        $this->assertEquals('v1', $feature->getOption('o1'));

        $feature->addOption('o1', 'v2');
        $this->assertEquals('v2', $feature->getOption('o1'));

        $feature->addOption('o1', 'v3', false);
        $this->assertEquals('v2', $feature->getOption('o1'));
    }
}
