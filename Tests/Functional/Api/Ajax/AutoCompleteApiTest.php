<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Integration\Api\Ajax;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class AutoCompleteApiTest extends WebTestCase
{
    public function testAutocompletionShouldFindNothingIfNoRecordsContainsGivenValue()
    {
        $client = static::createClient();
        $client->request('GET', '/test/user/autocomplete', [
            'filter' => [
                'search' => [
                    'value' => 'NoExistingValue',
                ]
            ]
        ]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getInternalResponse()->getContent());

        $this->assertEmpty($response);
    }

    public function testAutocompletionShouldFindRecordsContainingGivenValue()
    {
        $client = static::createClient();
        $client->request('GET', '/test/user/autocomplete', [
            'filter' => [
                'search' => [
                    'value' => 'ser 1',
                ]
            ]
        ]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getInternalResponse()->getContent());

        $this->assertCount(12, $response);
    }
}
