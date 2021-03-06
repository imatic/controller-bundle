<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;

class LoadUserData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 100; ++$i) {
            $user = new User($i);
            $user->setActive($i % 2 === 0);
            $user->setName('User ' . $i);
            $user->setAge(10 + ($i * 5));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
