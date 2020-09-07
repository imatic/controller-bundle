<?php declare(strict_types=1);
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
        $this->client->followRedirects();

        $activeUser = $this->getActiveUser();

        $this->client->request('PATCH', \sprintf('/test/user/activate/%s', $activeUser->getId()));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertFalse($activeUser->isActive());
    }

    /**
     * @return User
     */
    private function getActiveUser()
    {
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['active' => true]);
        $this->assertTrue($user->isActive());

        return $user;
    }
}
