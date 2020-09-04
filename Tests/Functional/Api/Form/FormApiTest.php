<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Functional\Form;

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class FormApiTest extends WebTestCase
{
    public function testFormShouldBeSuccessfullySubmitted()
    {
        // guard
        $this->assertNull($this->findUserByName('new user'));

        $crawler = $this->client->request('GET', '/test/user/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->filter('form')->form([
            'user[name]' => 'new user',
            'user[age]' => 23,
            'user[active]' => true,
        ]);
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

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
        return $this->getEntityManager()->getRepository(User::class)->findOneBy(['name' => $name]);
    }
}
