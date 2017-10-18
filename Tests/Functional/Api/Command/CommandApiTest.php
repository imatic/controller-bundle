<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Command;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CommandApiTest extends WebTestCase
{
    public function testCommandShouldGreetUser()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/test/user/greet/USERNAME');
        $this->assertEquals(200, $client->getInternalResponse()->getStatus());

        $this->assertContains('Hello USERNAME!', \trim($crawler->filter('#messages')->text()));
    }
}
