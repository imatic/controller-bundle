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
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;

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

    /**
     * @return CommandResultInterface
     */
    public function getResult()
    {
        $result = $this->command->execute();
        $this->message->addCommandMessage($result);

        return $result;
    }

    public function getResponse()
    {
        $result = $this->getResult();

        if ($result->hasException()) {
            throw $result->getException();
        }

        if ($result->has('response')) {
            $response = $result->get('response');
        } else {
            $name = $result->isSuccessful() ? 'success' : 'error';
            if (!$this->redirect->hasRedirect($name)) {
                throw new \RuntimeException(sprintf(
                    'Command "%s" has failed.',
                    $this->command->getCommandName()
                ));
            }

            $response = $this->response->createRedirect(
                $this->redirect->getRedirectUrl($name, ['result' => $result])
            );
        }

        return $response;
    }
}
