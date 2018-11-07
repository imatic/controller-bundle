<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Controller\Api\Query;

use Imatic\Bundle\ControllerBundle\Controller\Api\Api;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeatureTrait;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeature;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeatureTrait;

abstract class QueryApi extends Api
{
    use TemplateFeatureTrait;
    use DataFeatureTrait;

    /**
     * @var TemplateFeature
     */
    protected $template;

    /**
     * @var DataFeature
     */
    protected $data;

    public function __construct(RequestFeature $request, ResponseFeature $response, TemplateFeature $template, DataFeature $data)
    {
        parent::__construct($request, $response);
        $this->data = $data;
        $this->template = $template;
    }
}
