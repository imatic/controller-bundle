<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Form;

use Imatic\Bundle\ControllerBundle\Controller\Api\Command\CommandApi;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\CommandFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Form\FormFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Message\MessageFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\RedirectFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeatureTrait;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Response;

class FormApi extends CommandApi
{
    use DataFeatureTrait;
    use TemplateFeatureTrait;

    /**
     * @var TemplateFeature
     */
    protected $template;

    /**
     * @var DataFeature
     */
    protected $data;

    /**
     * @var FormFeature
     */
    private $form;

    public function __construct(
        RequestFeature $request,
        ResponseFeature $response,
        CommandFeature $command,
        RedirectFeature $redirect,
        MessageFeature $message,
        FormFeature $form,
        DataFeature $data,
        TemplateFeature $template
    )
    {
        parent::__construct($request, $response, $command, $redirect, $message);

        $this->form = $form;
        $this->data = $data;
        $this->template = $template;
    }

    public function form($form, $emptyValue = null)
    {
        $this->form->setName($form);
        $this->form->setEmptyValue($emptyValue);

        return $this;
    }

    public function edit(QueryObjectInterface $queryObject)
    {
        $item = $this->data->query('item', $queryObject);
        $this->form->setEmptyValue($item);
        $this->response->throwNotFoundUnless($item);

        return $this;
    }

    public function getResponse()
    {
        $request = $this->request->getCurrentRequest();

        $form = $this->form->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $result = $this->command->execute(['data' => $data]);
            $this->message->addCommandMessage($this->command->getBundleName(), $this->command->getCommandName(), $result);
            if ($result->isSuccessful()) {
                return $this->response->createRedirect($this->redirect->getSuccessRedirectUrl([
                    'result' => $result,
                    'data' => $data
                ]));
            }
        }

        $this->template->addTemplateVariable('form', $form->createView());
        $this->template->addTemplateVariables($this->data->all());

        return new Response($this->template->render());
    }
}
