UPGRADE FROM 3.x to 4.0
=======================

Services
--------

* The following service aliases have been removed; use their fully-qualified class name instead:

    - `imatic_controller.api.feature.data` use `Imatic\Bundle\ControllerBundle\Controller\Feature\Data\DataFeature`
    - `imatic_controller.api.feature.template` use `Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeature`
    - `imatic_controller.api.feature.form` use `Imatic\Bundle\ControllerBundle\Controller\Feature\Form\FormFeature`
    - `imatic_controller.api.feature.response` use `Imatic\Bundle\ControllerBundle\Controller\Feature\Response\ResponseFeature`
    - `imatic_controller.api.feature.request` use `Imatic\Bundle\ControllerBundle\Controller\Feature\Request\RequestFeature`
    - `imatic_controller.api.feature.command` use `Imatic\Bundle\ControllerBundle\Controller\Feature\Command\CommandFeature`
    - `imatic_controller.api.feature.redirect` use `Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect\RedirectFeature`
    - `imatic_controller.api.feature.message` use `Imatic\Bundle\ControllerBundle\Controller\Feature\Message\MessageFeature`
    - `imatic_controller.api.feature.security` use `Imatic\Bundle\ControllerBundle\Controller\Feature\Security\SecurityFeature`
    - `imatic_controller.api.show` use `Imatic\Bundle\ControllerBundle\Controller\Api\Show\ShowApi`
    - `imatic_controller.api.listing` use `Imatic\Bundle\ControllerBundle\Controller\Api\Listing\ListingApi`
    - `imatic_controller.api.form` use `Imatic\Bundle\ControllerBundle\Controller\Api\Form\FormApi`
    - `imatic_controller.api.command` use `Imatic\Bundle\ControllerBundle\Controller\Api\Command\CommandApi`
    - `imatic_controller.api.command.object` use `Imatic\Bundle\ControllerBundle\Controller\Api\Command\ObjectCommandApi`
    - `imatic_controller.api.command.batch` use `Imatic\Bundle\ControllerBundle\Controller\Api\Command\BatchCommandApi`
    - `imatic_controller.api.download` use `Imatic\Bundle\ControllerBundle\Controller\Api\Download\DownloadApi`
    - `imatic_controller.api.autocomplete` use `Imatic\Bundle\ControllerBundle\Controller\Api\Ajax\AutocompleteApi`
    - `imatic_controller.api.export` use `Imatic\Bundle\ControllerBundle\Controller\Api\Export\ExportApi`
    - `imatic_controller.api.import` use `Imatic\Bundle\ControllerBundle\Controller\Api\Import\ImportApi`
