<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Download;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DownloadApiTest extends WebTestCase
{
    public function testCommandShouldDownloadFile()
    {
        $this->client->request('GET', '/test/user/data');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('count: 100', $this->client->getInternalResponse()->getContent());
        $this->assertEquals('attachment; filename=userData', $this->client->getInternalResponse()->getHeader('Content-Disposition'));
    }
}
