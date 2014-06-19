<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Command;

use Imatic\Bundle\ControllerBundle\Controller\Api\Api;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\CommandFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\CommandFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Message\MessageFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\RedirectFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\RedirectFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;

class CommandApi extends Api
{
    use CommandFeatureTrait;
    use RedirectFeatureTrait;

    /**
     * @var CommandFeature
     */
    protected $command;

    /**
     * @var RedirectFeature
     */
    protected $redirect;

    /**
     * @var MessageFeature
     */
    protected $message;

    public function __construct(RequestFeature $request, ResponseFeature $response, CommandFeature $command, RedirectFeature $redirect, MessageFeature $message)
    {
        parent::__construct($request, $response);

        $this->command = $command;
        $this->redirect = $redirect;
        $this->message = $message;
    }

    public function command($commandName, array $commandParameters = [])
    {
        $this->command->setCommandName($commandName);
        $this->command->setCommandParameters($commandParameters);

        return $this;
    }

    public function getResponse()
    {
        $result = $this->command->execute();

        $this->message->addCommandMessage($result);
        $name = $result->isSuccessful() ? 'success' : 'error';

        if (!$this->redirect->hasRedirect($name)) {
            if ($result->hasException()) {
                throw $result->getException();
            } else {
                throw new \RuntimeException(sprintf(
                    'Command "%s" has failed.',
                    $this->command->getCommandName()
                ));
            }
        }

        return $this->response->createRedirect($this->redirect->getRedirectUrl($name, ['result' => $result]));
    }
}
