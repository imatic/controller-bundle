<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Controller;

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Filter\UserFilter;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler\UserActivateHandler;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler\UserCreateHandler;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler\UserDeleteHandler;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler\UserEditHandler;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler\UserGreetBatchHandler;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\Handler\UserGreetHandler;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserListQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Form\Type\UserType;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    use ApiTrait;

    /**
     * @Route("/autocomplete", name="app_user_autocomplete", methods={"GET"})
     */
    public function autoCompleteAction()
    {
        return $this->autocomplete()
            ->autocomplete(new UserListQuery())
            ->getResponse();
    }

    /**
     * @Route("/{id}", name="app_user_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function showAction($id)
    {
        return $this
            ->show(new UserQuery($id))
            ->setTemplateName('@AppImaticController/Test/show.html.twig')
            ->getResponse();
    }

    /**
     * @Route("", name="app_user_list", methods={"GET"})
     */
    public function listAction()
    {
        return $this
            ->listing(new UserListQuery())
            ->filter(UserFilter::class)
            ->defaultLimit(10)
            ->setTemplateName('@AppImaticController\Test\list.html.twig')
            ->getResponse();
    }

    /**
     * @Route("/edit/{id}", name="app_user_edit", methods={"GET", "PUT"})
     */
    public function editAction($id)
    {
        return $this
            ->form(UserType::class)
            ->commandName(UserEditHandler::class)
            ->edit(new UserQuery($id))
            ->successRedirect('app_user_edit', ['id' => $id])
            ->setTemplateName('@AppImaticController/Test/edit.html.twig')
            ->getResponse();
    }

    /**
     * @Route("/create", name="app_user_create", methods={"GET", "POST"})
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function createAction()
    {
        return $this
            ->form(UserType::class, new User())
            ->commandName(UserCreateHandler::class)
            ->successRedirect('app_user_edit', function (CommandResultInterface $result, User $user) {
                return ['id' => $user->getId()];
            })
            ->setTemplateName('@AppImaticController/Test/edit.html.twig')
            ->getResponse();
    }

    /**
     * @Route("/delete/{id}", name="app_user_delete", methods={"DELETE"})
     */
    public function deleteAction($id)
    {
        return $this
            ->command(UserDeleteHandler::class, ['user' => $id])
            ->redirect('app_user_list')
            ->getResponse();
    }

    /**
     * @Route("/activate/{id}", name="app_user_activate", methods={"PATCH"})
     */
    public function activateAction($id)
    {
        return $this
            ->objectCommand(UserActivateHandler::class, [], new UserQuery($id))
            ->redirect('app_user_list')
            ->getResponse();
    }

    /**
     * @Route("/greet/{username}", methods={"GET"})
     */
    public function greetAction($username)
    {
        return $this->command()
            ->command(UserGreetHandler::class, [
                'username' => $username,
            ])
            ->redirect('app_user_list')
            ->getResponse();
    }

    /**
     * @Route("/greet-batch")
     */
    public function greetBatchAction()
    {
        return $this
            ->batchCommand(UserGreetBatchHandler::class)
            ->redirect('app_user_list')
            ->getResponse();
    }

    /**
     * @Route("/data")
     */
    public function dataAction()
    {
        return $this->download()
            ->download(new \SplFileInfo(__DIR__ . '/../../../userData'))
            ->getResponse();
    }

    /**
     * @Route("/export")
     */
    public function exportAction()
    {
        return $this->export()
            ->export(new UserListQuery(), 'csv', 'users.csv')
            ->getResponse();
    }

    /**
     * @Route("/import", name="app_user_import")
     */
    public function importAction()
    {
        return $this->import()
            ->import('imatic_importexport.file', [
                'dataDefinition' => [
                    'name',
                    'age',
                    'active',
                ],
                'form' => UserType::class,
                'command' => UserCreateHandler::class,
            ])
            ->successRedirect('app_user_import_success')
            ->setTemplateName('@AppImaticController/Test/import.html.twig')
            ->getResponse();
    }

    /**
     * @return FormFactory
     */
    public function getFormFactory()
    {
        return $this->container->get('form.factory');
    }

    /**
     * @Route("import-success", name="app_user_import_success")
     */
    public function importSuccessAction()
    {
        return $this->render('@AppImaticController/Test/importSuccess.html.twig');
    }
}
