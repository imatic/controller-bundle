<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Config\Route("/company", service="imatic_directory.controller.company")
 * @Config\Security("has_role('ROLE_IMATIC_DIRECTORY_COMPANY_LIST')")
 */
class ExampleController1 implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ApiTrait;

    protected $config;

    public function __construct(CompanyCRUDControllerConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @Config\Route("")
     * @Config\Method("GET")
     * @Config\Security("has_role('ROLE_IMATIC_DIRECTORY_COMPANY_LIST')")
     */
    public function listAction()
    {
        return $this
            ->listing(new $this->config['list.query']($this->config['entity.class']))
//            ->filter('imatic_directory_company_filter')
            ->setTemplateName($this->config['list.template'])
            ->addTemplateVariable('config', $this->config)
            ->getResponse();
    }

    /**
     * @Config\Route("/autocomplete")
     * @Config\Method("GET")
     * @Config\Security("has_role('ROLE_IMATIC_DIRECTORY_COMPANY_LIST')")
     */
    public function autocompleteAction()
    {
        return $this
            ->autocomplete(new $this->config['autocomplete.query']($this->config['entity.class']))
            ->getResponse();
    }

    /**
     * @Config\Route("/{id}", requirements={"id"="\d+"})
     * @Config\Method("GET")
     * @Config\Template()
     * @Config\Security("has_role('ROLE_IMATIC_DIRECTORY_COMPANY_SHOW')")
     */
    public function showAction($id)
    {
        return $this
            ->show(new $this->config['item.query']($id, $this->config['entity.class']))
            ->setTemplateName($this->config['show.template'])
            ->addTemplateVariable('config', $this->config)
            ->getResponse();
    }

    /**
     * @Config\Route("/{id}/edit", requirements={"id"="\d+"})
     * @Config\Method({"GET", "PUT"})
     * @Config\Security("has_role('ROLE_IMATIC_DIRECTORY_COMPANY_EDIT')")
     */
    public function editAction($id, Request $request)
    {
        return $this
            ->form($this->config['edit.form'], null, ['data_class' => $this->config['entity.class']])
            ->commandName($this->config['edit.command'])
            ->edit(new $this->config['item.query']($id, $this->config['entity.class']))
            ->successRedirect($this->config['edit.redirect'], ['id' => $id])
            ->setTemplateName($this->config['edit.template'])
            ->getResponse();
    }

    /**
     * @Config\Route("/create")
     * @Config\Method({"GET", "POST"})
     * @Config\Security("has_role('ROLE_IMATIC_DIRECTORY_COMPANY_CREATE')")
     */
    public function createAction()
    {
        return $this
            ->form($this->config['create.form'], null, ['data_class' => $this->config['entity.class']])
            ->commandName($this->config['create.command'])
            ->successRedirect($this->config['create.redirect'], function (CommandResultInterface $result, $company) {
                return ['id' => $company->getId()];
            })
            ->setTemplateName($this->config['create.template'])
            ->getResponse();
    }

    /**
     * @Config\Route("/{id}/delete", requirements={"id"="\d+"})
     * @Config\Method("DELETE")
     * @Config\Security("has_role('ROLE_IMATIC_DIRECTORY_COMPANY_DELETE')")
     */
    public function deleteAction($id)
    {
        return $this
            ->command($this->config['delete.command'], ['company' => $id, 'entity.class' => $this->config['entity.class']])
            ->redirect($this->config['delete.redirect'])
            ->getResponse();
    }
}
