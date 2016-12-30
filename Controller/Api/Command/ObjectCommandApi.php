<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Command;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\CommandFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Message\MessageFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\RedirectFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class ObjectCommandApi extends CommandApi
{
    use DataFeatureTrait;

    /**
     * @var DataFeature
     */
    private $data;

    public function __construct(
        RequestFeature $request,
        ResponseFeature $response,
        CommandFeature $command,
        RedirectFeature $redirect,
        MessageFeature $message,
        DataFeature $data
    ) {
        parent::__construct($request, $response, $command, $redirect, $message);

        $this->data = $data;
    }

    public function objectCommand($commandName, array $commandParameters, QueryObjectInterface $queryObject)
    {
        $this->command->setCommandName($commandName);
        $this->command->setCommandParameters($commandParameters);

        $object = $this->data->query('object', $queryObject);
        $this->response->throwNotFoundUnless($object);

        $this->command->addCommandParameter('object', $object);

        return $this;
    }
}
