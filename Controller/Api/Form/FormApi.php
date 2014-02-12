<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Form;

use Imatic\Bundle\ControllerBundle\Controller\Api\CommandApi;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\CommandFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\CommandFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Form\FormFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Message\MessageFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\RedirectFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\RedirectFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeatureTrait;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class FormApi extends CommandApi
{
    use DataFeatureTrait;
    use TemplateFeatureTrait;
    use CommandFeatureTrait;
    use RedirectFeatureTrait;

    /**
     * @var FormFeature
     */
    private $form;

    /**
     * @var ResponseFeature
     */
    private $response;

    /**
     * @var RequestFeature
     */
    private $request;

    /**
     * @var RedirectFeature
     */
    private $redirect;

    /**
     * @var MessageFeature
     */
    private $message;

    public function __construct(
        CommandFeature $command,
        FormFeature $form,
        DataFeature $data,
        TemplateFeature $template,
        ResponseFeature $response,
        RequestFeature $request,
        RedirectFeature $redirect,
        MessageFeature $message
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
