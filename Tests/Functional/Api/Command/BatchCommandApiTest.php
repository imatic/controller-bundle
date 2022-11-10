<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Command;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Filter\UserFilter;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class BatchCommandApiTest extends WebTestCase
{
    public function testCommandShouldGreetUsers()
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('POST', '/test/user/greet-batch', [
            'selected' => [1, 2, 3],
            'query' => json_encode(['filter' => null, 'filter_type' => UserFilter::class]),
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertStringContainsString('Hromadná akce byla provedena pro 3', $crawler->filter('#messages')->text());
    }
}
