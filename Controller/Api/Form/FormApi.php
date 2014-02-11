<?php

namespace Imatic\Bundle\ControllerBundle\Api;

use Imatic\Bundle\ControllerBundle\Api\Feature\Data;
use Imatic\Bundle\ControllerBundle\Api\Feature\DataTrait;
use Imatic\Bundle\ControllerBundle\Api\Feature\FormTrait;
use Imatic\Bundle\ControllerBundle\Api\Feature\RequestTrait;
use Imatic\Bundle\ControllerBundle\Api\Feature\Template;
use Imatic\Bundle\ControllerBundle\Api\Feature\TemplateTrait;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

class FormApi extends CommandApi
{
    use DataTrait
    use TemplateTrait;
    use FormTrait;
    use RequestTrait;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(CommandExecutorInterface $commandExecutor, Data $data, Template $template, FormFactoryInterface $formFactory)
    {
        parent::__construct($commandExecutor, $data, $template);
        $this->formFactory = $formFactory;
    }

    public function form($form)
    {
        $this->setFormName($form);

        return $this;
    }

    public function edit(QueryObjectInterface $queryObject)
    {
        $this->setFormEmptyValue($this->data->findOne($queryObject, true));
    }

    public function getResponse()
    {
        $request = $this->getRequest();

        $form = $this->formFactory->create($this->getFormName(), $this->getFormEmptyValue());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $command->handle();
//            $message->success();
//            $redirect->redirect();
        } else {
//            $message->error();
        }

        $this->template->addTemplateVariables($this->data->all());

        return new Response($this->template->render());
    }
}
