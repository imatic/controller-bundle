<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Form;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class FormApiTest extends WebTestCase
{
    public function testFormShouldBeSuccessfullySubmitted()
    {
        // guard
        $this->assertNull($this->findUserByName('new user'));

        $client = static::createClient();
        $crawler = $client->request('GET', '/test/user/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('form')->form([
            'app_imatic_controller_user[name]' => 'new user',
            'app_imatic_controller_user[age]' => 23,
            'app_imatic_controller_user[active]' => true,
        ]);
        $client->submit($form);
        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $newUser = $this->findUserByName('new user');
        $this->assertNotNull($newUser);
        $this->assertEquals('new user', $newUser->getName());
        $this->assertEquals(23, $newUser->getAge());
        $this->assertTrue($newUser->isActive());
    }

    /**
     * @param string $name
     *
     * @return User|null
     */
    private function findUserByName($name)
    {
        $userRepository = $this->getEntityManager()->getRepository('AppImaticControllerBundle:User');

        return $userRepository->findOneByName($name);
    }
}
