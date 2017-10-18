<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Api\Import;

use Imatic\Bundle\ControllerBundle\Controller\Api\Api;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\RedirectFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\RedirectFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeatureTrait;
use Imatic\Bundle\ImportExportBundle\Import\Importer;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ImportApi extends Api
{
    use TemplateFeatureTrait;
    use RedirectFeatureTrait;

    /** @var TemplateFeature */
    protected $template;

    /** @var RedirectFeature */
    protected $redirect;

    /** @var Importer */
    protected $importer;

    public function __construct(
        RequestFeature $request,
        ResponseFeature $response,
        TemplateFeature $template,
        RedirectFeature $redirect,
        Importer $importer
    ) {
        parent::__construct($request, $response);

        $this->template = $template;
        $this->redirect = $redirect;
        $this->importer = $importer;
    }

    public function import($name, array $options = [])
    {
        if ($this->request->getCurrentRequest()->isMethod('POST')) {
            $this->importer->import($name, $options);
        }

        return $this;
    }

    public function getResult()
    {
        return [];
    }

    public function getResponse()
    {
        if ($this->request->getCurrentRequest()->isMethod('POST')) {
            return $this->response->createRedirect($this->redirect->getSuccessRedirectUrl());
        }

        return new Response($this->template->render());
    }
}
