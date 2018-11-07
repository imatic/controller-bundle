<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Controller\Api\Show;

use Imatic\Bundle\ControllerBundle\Controller\Api\Query\ItemQueryApi;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use Symfony\Component\HttpFoundation\Response;

class ShowApi extends ItemQueryApi
{
    public function show(SingleResultQueryObjectInterface $queryObject)
    {
        $result = $this->data->query('item', $queryObject);
        $this->response->throwNotFoundUnless($result);

        return $this;
    }

    public function getResponse()
    {
        $this->security->checkDataAuthorization($this->data->all());

        $this->template->addTemplateVariables($this->data->all());

        return new Response($this->template->render());
    }
}
