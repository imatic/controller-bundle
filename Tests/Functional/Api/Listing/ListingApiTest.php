<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Listing;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ListingApiTest extends WebTestCase
{
    public function testListingShouldShowListOfUsers()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/test/user');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $records = $crawler->filter('#action table tr');
        $this->assertEquals(20, $records->count());
        $this->assertEquals('User 1 (Inactive)', trim($records->first()->filter('td')->first()->text()));
        $this->assertEquals('User 20 (Active)', trim($records->last()->filter('td')->first()->text()));
    }
}
