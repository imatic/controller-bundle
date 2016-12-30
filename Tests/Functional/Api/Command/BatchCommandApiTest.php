<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Command;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class BatchCommandApiTest extends WebTestCase
{
    public function testCommandShouldGreetUsers()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('POST', '/test/user/greet-batch', [
            'greet' => 'command',
            'selected' => [
                'user1',
                'user2',
                'user3',
            ],
        ]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('Hromadná akce byla provedena pro 3', $crawler->filter('#messages')->text());
    }
}
