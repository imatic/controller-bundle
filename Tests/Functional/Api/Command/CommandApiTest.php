<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Command;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CommandApiTest extends WebTestCase
{
    public function testCommandShouldGreetUser()
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/test/user/greet/USERNAME');
        $this->assertEquals(200, $this->client->getInternalResponse()->getStatusCode());

        $this->assertStringContainsString('Hello USERNAME!', \trim($crawler->filter('#messages')->text()));
    }
}
