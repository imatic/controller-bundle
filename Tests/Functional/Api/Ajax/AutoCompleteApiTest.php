<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Api\Ajax;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class AutoCompleteApiTest extends WebTestCase
{
    public function testAutocompletionShouldFindNothingIfNoRecordsContainsGivenValue()
    {
        $this->client->request('GET', '/test/user/autocomplete', [
            'filter' => [
                'search' => [
                    'value' => 'NoExistingValue',
                ],
            ],
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $response = \json_decode($this->client->getInternalResponse()->getContent());

        $this->assertEmpty($response);
    }

    public function testAutocompletionShouldFindRecordsContainingGivenValue()
    {
        $this->client->request('GET', '/test/user/autocomplete', [
            'filter' => [
                'search' => [
                    'value' => 'ser 1',
                ],
            ],
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $response = \json_decode($this->client->getInternalResponse()->getContent());

        $this->assertCount(12, $response);
    }
}
