<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Download;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DownloadApiTest extends WebTestCase
{
    public function testCommandShouldDownloadFile()
    {
        $client = static::createClient();
        $client->request('GET', '/test/user/data');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('count: 100', $client->getInternalResponse()->getContent());
        $this->assertEquals('attachment; filename=userData', $client->getInternalResponse()->getHeader('Content-Disposition'));
    }
}
