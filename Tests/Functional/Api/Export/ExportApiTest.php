<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Api\Export;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ExportApiTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        if (!\class_exists('Imatic\Bundle\ImportExportBundle\ImaticImportExportBundle')) {
            $this->markTestSkipped('ImaticImportExportBundle is not installed.');
        }
    }

    public function testExportShouldExportUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/test/user/export');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = $client->getResponse();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\BinaryFileResponse', $response);
    }
}
