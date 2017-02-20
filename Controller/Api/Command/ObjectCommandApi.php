<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Command;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\CommandFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Message\MessageFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\RedirectFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Security\SecurityFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Security\SecurityFeatureTrait;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class ObjectCommandApi extends CommandApi
{
    use DataFeatureTrait;
    use SecurityFeatureTrait;

    /**
     * @var DataFeature
     */
    private $data;

    /**
     * @var SecurityFeature
     */
    private $security;

    public function __construct(
        RequestFeature $request,
        ResponseFeature $response,
        CommandFeature $command,
        RedirectFeature $redirect,
        MessageFeature $message,
        DataFeature $data,
        SecurityFeature $security
    )
    {
        parent::__construct($request, $response, $command, $redirect, $message);

        $this->data = $data;
        $this->security = $security;
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

    public function getResult()
    {
        $this->security->checkDataAuthorization($this->data->all());

        return parent::getResult();
    }
}
