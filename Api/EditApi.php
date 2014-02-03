<?php

namespace Imatic\Bundle\ControllerBundle\Api;

use Imatic\Bundle\ControllerBundle\Api\Feature\DataTrait;
use Imatic\Bundle\ControllerBundle\Api\Feature\TemplateTrait;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Response;

class EditApi extends CommandApi
{
    use DataTrait
    use TemplateTrait;

    /*
     * form life cycle
     * handle akce
     * redirect
     */

    public function show(QueryObjectInterface $queryObject)
    {
        $this->template->setTemplateVariableName('item');
        $this->data->addQuery('item', $queryObject);

        return $this;
    }

    public function getResponse()
    {
        $item = $this->findOr404('item');
        $form = $this->getForm();

        $form->bind();
        if ($form->valid()) {
            $command->handle();
            $message->success();
            $redirect->redirect();
        } else {
            $message->error();
        }

        $this->template->addTemplateVariable($this->template->getTemplateVariableName(), $item);

        return new Response($this->template->render());
    }
}
