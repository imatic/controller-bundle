<?php
namespace Imatic\Bundle\TestsTemplateBundle\Tests\Fixtures\TestProject;

use Imatic\Bundle\TestingBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @return string
     */
    protected static function getKernelClass()
    {
        return 'Imatic\Bundle\TestsTemplateBundle\Tests\Fixtures\TestProject\TestKernel';
    }
}
