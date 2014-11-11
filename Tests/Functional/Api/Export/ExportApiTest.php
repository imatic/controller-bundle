<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Api\Export;

use Imatic\Bundle\ImportExportBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ExportApiTest extends WebTestCase
{
    public function testExportShouldExportUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/test/user/export');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = $client->getResponse();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\BinaryFileResponse', $response);
    }
}
