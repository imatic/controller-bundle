<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Form;

use Imatic\Bundle\ControllerBundle\Controller\Api\CommandApi;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\Command;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\CommandTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\Data;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Form\Form;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Message\Message;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\Redirect;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\RedirectTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\Request;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\Response;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\Template;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateTrait;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class FormApi extends CommandApi
{
    use DataTrait;
    use TemplateTrait;
    use CommandTrait;
    use RedirectTrait;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Redirect
     */
    private $redirect;

    /**
     * @var Message
     */
    private $message;

    public function __construct(
        Command $command,
        Form $form,
        Data $data,
        Template $template,
        Response $response,
        Request $request,
        Redirect $redirect,
        Message $message
    )
    {
        parent::__construct($command, $data, $template);

        $this->form = $form;
        $this->response = $response;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->message = $message;
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

        return new HttpResponse($this->template->render());
    }
}
