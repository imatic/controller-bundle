<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Show;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ShowApiTest extends WebTestCase
{
    public function testShowShouldShowTheUsers()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/test/user/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals('user: User 1', trim($crawler->filter('#action')->text()));
    }
}
