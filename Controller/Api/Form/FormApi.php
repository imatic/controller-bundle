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
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\Form\FormInterface;
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

    public function form($type, $emptyValue = null, array $options = [])
    {
        return $this->namedForm($type, $type, $emptyValue, $options);
    }

    public function namedForm($name, $type, $emptyValue = null, array $options = [])
    {
        $this->form->setName($name);
        $this->form->setType($type);
        $this->form->setEmptyValue($emptyValue);
        $this->form->setOptions($options);
        $this->form->addOption('method', 'POST');

        return $this;
    }

    public function edit(QueryObjectInterface $queryObject, \Closure $closure = null)
    {
        $item = $this->data->query('item', $queryObject);
        $this->response->throwNotFoundUnless($item);

        if ($closure) {
            $item = $closure($item, $this->data);
            $this->data->set('item', $item);
        }

        $this->form->setEmptyValue($item);
        $this->form->addOption('method', 'PUT');

        return $this;
    }

    public function getResponse()
    {
        $handle = $this->handleForm();
        /** @var FormInterface $form */
        $form = $handle['form'];
        /** @var CommandResultInterface $result */
        $result = $handle['result'];

        $this->template->addTemplateVariable('form', $form->createView());
        $this->template->addTemplateVariables($this->data->all());

        if ($result && $result->isSuccessful()) {
            return $this->response->createRedirect($this->redirect->getSuccessRedirectUrl([
                'result' => $result,
                'data' => $form->getData(),
            ]));
        } else {
            return new Response($this->template->render(), ($form->isSubmitted() && !$form->isValid() ? 400 : 200));
        }
    }

    public function handleForm()
    {
        $request = $this->request->getCurrentRequest();

        $this->form->addOption('action', $this->request->getCurrentUri());
        $form = $this->form->getForm();
        $form->handleRequest($request);
        $result = null;

        if ($form->isValid()) {
            $action = $this->form->getSubmittedName($form);
            $data = $form->getData();
            $result = $this->command->execute(['data' => $data, 'action' => $action]);
            $this->message->addCommandMessage($result);
        }

        return ['form' => $form, 'result' => $result];
    }
}
