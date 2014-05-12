<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Controller;

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
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

    /**
     * @Config\Route("/autocomplete", name="app_user_autocomplete")
     * @Config\Method("GET")
     */
    public function autoCompleteAction()
    {
        return $this->autocomplete()
            ->autocomplete(new UserListQuery())
            ->getResponse()
        ;
    }

    /**
     * @Config\Route("/{id}", name="app_user_show", requirements={"id"="\d+"})
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
            ->listing(new UserListQuery())
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

    /**
     * @Config\Route("/greet/{username}")
     * @Config\Method("GET")
     */
    public function greetAction($username)
    {
        return $this->command()
            ->command('user.greet', [
                'username' => $username,
            ])
            ->redirect('app_user_list')
            ->getResponse()
        ;
    }

    /**
     * @Config\Route("/greet-batch")
     */
    public function greetBatchAction()
    {
        return $this
            ->batchCommand('user.greet.batch')
            ->redirect('app_user_list')
            ->getResponse();
    }

    /**
     * @Config\Route("/data")
     */
    public function dataAction()
    {
        return $this->download()
            ->download(new \SplFileInfo(__DIR__ . '/../../../userData'))
            ->getResponse()
        ;
    }
}
