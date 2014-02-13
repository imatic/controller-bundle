<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Controller;

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Controller\Api\Command\CommandApiTrait;
use Imatic\Bundle\ControllerBundle\Controller\Api\Command\ObjectCommandApiTrait;
use Imatic\Bundle\ControllerBundle\Controller\Api\Form\FormApiTrait;
use Imatic\Bundle\ControllerBundle\Controller\Api\Listing\ListingApiTrait;
use Imatic\Bundle\ControllerBundle\Controller\Api\Show\ShowApiTrait;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserListQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * @Config\Route("/user")
 */
class UserController implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ApiTrait;
    use CommandApiTrait;
    use ObjectCommandApiTrait;
    use ShowApiTrait;
    use FormApiTrait;
    use ListingApiTrait;

    /**
     * @Config\Route("/{id}", name="app_user_show")
     * @Config\Method("GET")
     */
    public function showAction($id)
    {
        return $this
            ->show(new UserQuery($id))
            ->setTemplateName('AppImaticControllerBundle:Test:show.html.twig')
            ->getResponse();
    }

    /**
     * @Config\Route("", name="app_user_list")
     * @Config\Method("GET")
     */
    public function listAction()
    {
        return $this
            ->listing(new UserListQuery(3))
            ->setTemplateName('AppImaticControllerBundle:Test:list.html.twig')
            ->getResponse();
    }

    /**
     * @Config\Route("/edit/{id}", name="app_user_edit")
     * @Config\Method({"GET", "POST"})
     */
    public function editAction($id)
    {
        return $this
            ->form('app_imatic_controller_user')
            ->commandName('user.edit')
            ->edit(new UserQuery($id))
            ->successRedirect('app_user_edit', ['id' => $id])
            ->setTemplateName('AppImaticControllerBundle:Test:edit.html.twig')
            ->getResponse();
    }

    /**
     * @Config\Route("/create", name="app_user_create")
     * @Config\Method({"GET", "PUT"})
     */
    public function createAction()
    {
        return $this
            ->form('app_imatic_controller_user', new User())
            ->commandName('user.create')
            ->successRedirect('app_user_edit', function (CommandResultInterface $result, User $user) {
                return ['id' => $user->getId()];
            })
            ->setTemplateName('AppImaticControllerBundle:Test:edit.html.twig')
            ->getResponse();
    }

    /**
     * @Config\Route("/delete/{id}", name="app_user_delete")
     * @Config\Method("DELETE")
     */
    public function deleteAction($id)
    {
        return $this
            ->command('user.delete', ['user' => $id])
            ->redirect('app_user_list')
            ->getResponse();
    }

    /**
     * @Config\Route("/activate/{id}", name="app_user_activate")
     * @Config\Method("PATCH")
     */
    public function activateAction($id)
    {
        return $this
            ->objectCommand('user.activate', [], new UserQuery($id))
            ->redirect('app_user_list')
            ->getResponse();
    }
}
