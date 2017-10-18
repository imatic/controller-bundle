<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Command;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ObjectCommandApiTest extends WebTestCase
{
    public function testCommandShouldDeactivateUser()
    {
        $client = static::createClient();
        $client->followRedirects();

        $activeUser = $this->getActiveUser();

        $client->request('PATCH', \sprintf('/test/user/activate/%s', $activeUser->getId()));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->getEntityManager()->refresh($activeUser);
        $this->assertFalse($activeUser->isActive());
    }

    /**
     * @return User
     */
    private function getActiveUser()
    {
        $user = $this->getEntityManager()->getRepository('AppImaticControllerBundle:User')->findOneByActive(true);
        $this->assertTrue($user->isActive());

        return $user;
    }
}
