<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Listing;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;

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
        $this->assertEquals(10, $records->count());
        $this->assertEquals('User 1 (Inactive)', trim($records->first()->filter('td')->first()->text()));
        $this->assertEquals('User 10 (Active)', trim($records->last()->filter('td')->first()->text()));
    }

    /** @test */
    public function shouldUseFilter()
    {
        $client = static::createClient();

        /*
         * Filter all users with names containing "User 1"
         *
         * Should be User 1 and User 10 to User 19 (11 users).
         */
        $displayCriteria = [
            'filter' => [
                'search' => [
                    'operator' => FilterOperatorMap::OPERATOR_CONTAINS,
                    'value'    => 'User 1',
                ],
            ],
        ];

        $crawler = $client->request('GET', '/test/user?' . http_build_query($displayCriteria));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $records = $crawler->filter('#action table tr');

        $this->assertEquals(10, $records->count());
        $this->assertEquals('User 1 (Inactive)', trim($records->first()->filter('td')->first()->text()));
        $this->assertEquals('User 18 (Active)', trim($records->last()->filter('td')->first()->text()));

    }

    /** @test */
    public function shouldUsePager()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/test/user?page=2');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $records = $crawler->filter('#action table tr');
        $this->assertEquals(10, $records->count());
        $this->assertEquals('User 11 (Inactive)', trim($records->first()->filter('td')->first()->text()));
        $this->assertEquals('User 20 (Active)', trim($records->last()->filter('td')->first()->text()));
    }
}
