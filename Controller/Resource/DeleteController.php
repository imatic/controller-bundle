<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Symfony\Component\HttpFoundation\Request;

class DeleteController extends ResourceController
{
    public function deleteAction(Request $request, $id)
    {
        $config = $this->getActionConfig();

        if ($request->isMethod('delete')) {
            return $this
                ->objectCommand(
                    $config['command'],
                    ['class' => $config['entity']],
                    new $config['query']($id, $config['entity'])
                )
                ->redirect($config['redirect'])
                ->addDataAuthorizationCheck(\strtoupper($config['name']), 'object')
                ->enableDataAuthorization($config['data_authorization'])
                ->getResponse();
        }
        return $this->show(new $config['query']($id, $config['entity']))
                ->addDataAuthorizationCheck(\strtoupper($config['name']), 'item')
                ->enableDataAuthorization($config['data_authorization'])
                ->setTemplateName($config['template'])
                ->addTemplateVariable('action', $config)
                ->addTemplateVariable('resource', $this->getResourceConfig())
                ->getResponse();
    }
}
