# Fluent interface

- Fluent interace is using [ApiTrait](/Controller/Api/ApiTrait.php) to make available several actions in a [controller](https://symfony.com/doc/current/controller.html).
- Each controller needs to have `$container` property which can be achieved:
    - by extending `Symfony\Bundle\FrameworkBundle\Controller\Controller`
    - by implementing `Symfony\Component\DependencyInjection\ContainerAwareInterface`


## [ApiTrait](/Controller/Api/ApiTrait.php)

- Trait used in controllers.
- Provides methods for various types of actions.
- Each method returns one of `api` objects with fluent interface and calls method with the same name on the api.
- Note that all examples below are extracted from testing [UserController](/Tests/Fixtures/TestProject/ImaticControllerBundle/Controller/UserController.php) so feel free to see the source for more details. Requests to the controller can be seen in [functional tests of the api](/Tests/Functional/Api).

### autocomplete

- Api used for autocompletion.
- class: [AutocompleteApi](/Controller/Api/Ajax/AutocompleteApi.php)
- methods:
    - `autocomplete`:
        - arguments:
            - `$queryObject`:
                - Query object used for autocompletion.
                - It is expected to have `search` filter on which `TextRule` will be applied.
    - `labelFunction`:
        - arguments:
           - `function` - function accepting a record and returning label of the record (default is `(string) $record`)
    - `identifierFunction`:
        - arguments:
            - `function` - function accepting a record and returning identifier of the record (default is `$record->getId()`)
    - `getResponse`:
        - returns json response:
            - array of objects with keys
                - `id` - identifier of the record
                - `text` - text representation of the record

#### Example

```php
<?php

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserListQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller

/**
 * @Config\Route("/user")
 */
class UserController extends Controller
{
    use ContainerAwareTrait;
    use ApiTrait;

    /**
     * @Config\Route("/autocomplete", name="app_user_autocomplete")
     * @Config\Method("GET")
     */
    public function autoCompleteAction()
    {
        return $this->autocomplete()
            ->autocomplete(new UserListQuery())
            ->getResponse()
        ;
    }
}
```

### <a name="command"></a> command

- Api used for executing commands.
- class: [CommandApi](/Controller/Api/Command/CommandApi.php)
- methods:
    - `command`:
        - arguments:
            - `$commandName` - name of the command to execute
            - `$commandParameters` - parameters passed to the command
    - `commandParameters`:
        - arguments:
            - `$commandParameters` - additional parameters passed to the command
    - `getResult`:
        - returns result of the command
    - `successRedirect`:
        - Sets route to which user will be redirected after successful execution of the command.
    - `errorRedirect`:
        - Sets route to which user will be redirected after unsuccessful execution of the command.
    - `redirect`:
        - It sets `successRedirect` and `errorRedirect` both to the same route.
    - `getResponse`:
        - returns response:
            - If command returned result with `response` data, it returns that data.
            - If command was successful, it returns response set with `successRedirect`
            - Otherwise it returns response set with `errorRedirect`

#### Example

```php
<?php

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller

/**
 * @Config\Route("/user")
 */
class UserController extends Controller
{
    use ContainerAwareTrait;
    use ApiTrait;

    /**
     * @Config\Route("/delete/{id}", name="app_user_delete")
     * @Config\Method("DELETE")
     */
    public function deleteAction($id)
    {
        return $this
            ->command('user.delete', ['user' => $id])
            ->redirect('app_user_list')
            ->getResponse();
    }
}
```

### batchCommand

- Api used for executing batch commands (extends [command api](#command)).
- class: [BatchCommandApi](/Controller/Api/Command/BatchCommandApi.php)
- All methods of CommandApi + `batchCommand`:
    - arguments:
        - `$allowedCommands`
            - array of allowed commands (or single command)

#### Example

```php
<?php

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller

/**
 * @Config\Route("/user")
 */
class UserController extends Controller
{
    use ContainerAwareTrait;
    use ApiTrait;

    /**
     * @Config\Route("/greet-batch")
     */
    public function greetBatchAction()
    {
        return $this
            ->batchCommand('user.greet.batch')
            ->redirect('app_user_list')
            ->getResponse();
    }
}
```

### objectCommand

- Api used for executing command on result of query object (extends [command api](#command)).
- class: [ObjectCommandApi](/Controller/Api/Command/ObjectCommandApi.php)
- methods:
    - `objectCommand`
        - arguments:
            - `$commandName`
                - Name of the command to execute.
            - `$commandParameters`
                - Parameters passed to the command.
            - `$queryObject`
                - Query object of which result will be passed to the command as `object` parameter.
    - `addValue`
        - arguments:
            - `$name`
                - Name under which result will be stored into `DataFeature`.
            - `$queryObject`
                - Query object of which result will be stored into `DataFeature`.
            - `$displayCriteria`
                - `DisplayCriteria` used when executing the query object.
    - `getValue`
        - method returning result of query object previously stored with `addValue`
    - `enableDataAuthorization`
        - Enables checks if user has permission to perform actions.
    - `addDataAuthorizationCheck`
        - arguments:
            - `$attributes`
                - Attributes to check on the result.
            - `$dataKey`
            - `$callback` (optional)
                - Function called before check is done (accepts result, returns result).

#### Example

```php
<?php

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller

/**
 * @Config\Route("/user")
 */
class UserController extends Controller
{
    use ContainerAwareTrait;
    use ApiTrait;

    /**
     * @Config\Route("/activate/{id}", name="app_user_activate")
     * @Config\Method("PATCH")
     */
    public function activateAction($id)
    {
        return $this
            ->objectCommand('user.activate', [], new UserQuery($id))
            ->redirect('app_user_list')
            ->getResponse();
    }
}
```

### download

- Api used for downloading files.
- class: [DownloadApi](/Controller/Api/Download/DownloadApi.php)
- methods:
    - `download`
        - arguments:
            - `$object`
                - Object to download. Can be one of:
                    - `SplFileInfo`
                    - `Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface` (the query object result has to be object implementing `FileObjectInterface`)
                    - `Imatic\Bundle\DocumentBundle\File\FileObjectInterface`
            - `$forceDownload`
                - If true, disposition is `attachment`, `inline` otherwise.
            - `$name`
                - Custom name of the file as which the `$object` is downloaded.
    - `addValue`
        - arguments:
            - `$name`
                - Name under which result will be stored into `DataFeature`.
            - `$queryObject`
                - Query object of which result will be stored into `DataFeature`.
            - `$displayCriteria`
                - `DisplayCriteria` used when executing the query object.
    - `getValue`
        - method returning result of query object previously stored with `addValue`
    - `enableDataAuthorization`
        - Enables checks if user has permission to perform actions.
    - `addDataAuthorizationCheck`
        - arguments:
            - `$attributes`
                - Attributes to check on the result.
            - `$dataKey`
            - `$callback` (optional)
                - Function called before check is done (accepts result, returns result).
    - `enableDataAuthorization`
        - Enables checks if user has permission to perform actions.
    - `addDataAuthorizationCheck`
        - arguments:
            - `$attributes`
                - Attributes to check on the result.
            - `$dataKey`
            - `$callback` (optional)
                - Function called before check is done (accepts result, returns result).
    - `getResponse` - returns `BinaryFileResponse`

#### Example

```php
<?php

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller

/**
 * @Config\Route("/user")
 */
class UserController extends Controller
{
    use ContainerAwareTrait;
    use ApiTrait;

    /**
     * @Config\Route("/data")
     */
    public function dataAction()
    {
        return $this->download()
            ->download(new \SplFileInfo(__DIR__ . '/../../../userData'))
            ->getResponse()
        ;
    }
}
```

### form

- Api used for working with forms (extends [command api](#command)).
- class: [FormApi](/Controller/Api/Form/FormApi.php)
- methods:
    - `form`
        - Creates a form.
    - `namedForm`
        - Creates a named form.
    - `edit`
        - Passes query object result into the form data.
        - arguments:
            - `$queryObject`
                - Query object of which result is set to the form.
            - `$closure` (optional)
                - Closure taking result of query object as first argument and returning data which should be set to the form.
    - `addValue`
        - Value is passed into template.
        - arguments:
            - `$name`
                - Name under which result will be stored into `DataFeature`.
            - `$queryObject`
                - Query object of which result will be stored into `DataFeature`.
            - `$displayCriteria`
                - `DisplayCriteria` used when executing the query object.
    - `getValue`
        - method returning result of query object previously stored with `addValue`
    - `addDataAuthorizationCheck`
        - arguments:
            - `$attributes`
                - Attributes to check on the result.
            - `$dataKey`
            - `$callback` (optional)
                - Function called before check is done (accepts result, returns result).
    - `enableDataAuthorization`
        - Enables checks if user has permission to perform actions.
    - `setTemplateName`
        - Sets template used for rendering form.
    - `addTemplateVariable`
        - Adds variable into the template.
    - `addTemplateVariables`
        - Adds multiple variables into the template.
    - `handleForm`
        - Submits the form. In case the form is valid, it executes the command.
        - Returns array with keys:
            - `form` - The form.
            - `result` - Result of the command (null in case form is invalid).
    - `getResponse`
        - Returns either `Response` with rendered form (template methods are used to specify details) or `RedirectResponse` in case form submission was successful (uses route from `successRedirect` method).

#### Example

```php
<?php

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller

/**
 * @Config\Route("/user")
 */
class UserController extends Controller
{
    use ContainerAwareTrait;
    use ApiTrait;

    /**
     * @Config\Route("/edit/{id}", name="app_user_edit")
     * @Config\Method({"GET", "POST"})
     */
    public function editAction($id)
    {
        return $this
            ->form(UserType::class)
            ->commandName('user.edit')
            ->edit(new UserQuery($id))
            ->successRedirect('app_user_edit', ['id' => $id])
            ->setTemplateName('AppImaticControllerBundle:Test:edit.html.twig')
            ->getResponse();
    }

    /**
     * @Config\Route("/create", name="app_user_create")
     * @Config\Method({"GET", "PUT"})
     */
    public function createAction()
    {
        return $this
            ->form(UserType::class, new User())
            ->commandName('user.create')
            ->successRedirect('app_user_edit', function (CommandResultInterface $result, User $user) {
                return ['id' => $user->getId()];
            })
            ->setTemplateName('AppImaticControllerBundle:Test:edit.html.twig')
            ->getResponse();
    }
}
```

### namedForm

- It works the same way as `form` method, except it calls different method on the api.
- class: [FormApi](/Controller/Api/Form/FormApi.php)

### listing

- Api used for showing multiple records.
- class: [ListingApi](/Controller/Api/Listing/ListingApi.php)
- methods:
    - `listing`
        - arguments
            - `queryObject`
                - Query object of which result will be shown in the template.
            - `displayCriteria` (optional)
    - `filter`
        - Filter used for the query object.
    - `defaultSort`
    - `defaultLimit`
    - `enablePersistentDisplayCriteria` (disabled by default)
    - `disablePersistentDisplayCriteria` (disabled by default)
    - `disableCountQuery` (enabled by default)
    - `enableCountQuery` (enabled by default)
    - `componentId`
    - `addValue`
        - Value is passed into template.
        - arguments:
            - `$name`
                - Name under which result will be stored into `DataFeature`.
            - `$queryObject`
                - Query object of which result will be stored into `DataFeature`.
            - `$displayCriteria`
                - `DisplayCriteria` used when executing the query object.
    - `getValue`
        - method returning result of query object previously stored with `addValue`
    - `setTemplateName`
        - Sets template used for rendering.
    - `addTemplateVariable`
        - Adds variable into the template.
    - `addTemplateVariables`
        - Adds multiple variables into the template.
    - `getResponse`
        - Returns `Response` with rendered template.

#### Example

```php
<?php

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserListQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller

/**
 * @Config\Route("/user")
 */
class UserController extends Controller
{
    use ContainerAwareTrait;
    use ApiTrait;

    /**
     * @Config\Route("", name="app_user_list")
     * @Config\Method("GET")
     */
    public function listAction()
    {
        return $this
            ->listing(new UserListQuery())
            ->setTemplateName('AppImaticControllerBundle:Test:list.html.twig')
            ->getResponse();
    }
}
```


### show

- Api used for showing detail of sigle record.
- class: [ShowApi](/Controller/Api/Show/ShowApi.php)
- methods:
    - `show`
        - Specifies query object of which result will be shown.
    - `addDataAuthorizationCheck`
        - arguments:
            - `$attributes`
                - Attributes to check on the result.
            - `$dataKey`
            - `$callback` (optional)
                - Function called before check is done (accepts result, returns result).
    - `enableDataAuthorization`
        - Enables checks if user has permission to perform actions.
    - `addValue`
        - Value is passed into template.
        - arguments:
            - `$name`
                - Name under which result will be stored into `DataFeature`.
            - `$queryObject`
                - Query object of which result will be stored into `DataFeature`.
            - `$displayCriteria`
                - `DisplayCriteria` used when executing the query object.
    - `getValue`
        - method returning result of query object previously stored with `addValue`
    - `setTemplateName`
        - Sets template used for rendering.
    - `addTemplateVariable`
        - Adds variable into the template.
    - `addTemplateVariables`
        - Adds multiple variables into the template.
    - `getResponse`
        - Returns `Response` with rendered template.

#### Example

```php
<?php

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller

/**
 * @Config\Route("/user")
 */
class UserController extends Controller
{
    use ContainerAwareTrait;
    use ApiTrait;

    /**
     * @Config\Route("/{id}", name="app_user_show", requirements={"id"="\d+"})
     * @Config\Method("GET")
     */
    public function showAction($id)
    {
        return $this
            ->show(new UserQuery($id))
            ->setTemplateName('AppImaticControllerBundle:Test:show.html.twig')
            ->getResponse();
    }
}
```

### import

- Api used for importing data (requires [ImaticImportExportBundle](https://bitbucket.org/imatic/imatic-importexportbundle) bundle to be installed).
- class: [ImportApi](/Controller/Api/Import/ImportApi.php)
- methods:
    - `import`
        - arguments:
            - `$name` - Name of the importer.
            - `$options` - Options for the importer.
    - `successRedirect`:
        - Sets route to which user will be redirected after successful import.
    - `setTemplateName`
        - Sets template used for rendering.
    - `addTemplateVariable`
        - Adds variable into the template.
    - `addTemplateVariables`
        - Adds multiple variables into the template.
    - `getResponse`
        - Returns `Response` with rendered template or `RedirectResponse` in case import was requested (`POST` request was made).

#### Example

```php
<?php

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller

/**
 * @Config\Route("/user")
 */
class UserController extends Controller
{
    use ContainerAwareTrait;
    use ApiTrait;

    /**
     * @Config\Route("/import", name="app_user_import")
     */
    public function importAction()
    {
        return $this->import()
            ->import('imatic_importexport.file', [
                'dataDefinition' => [
                    'name',
                    'age',
                    'active',
                ],
                'form' => 'app_imatic_controller_user',
                'command' => 'user.create',
            ])
            ->successRedirect('app_user_import_success')
            ->setTemplateName('AppImaticControllerBundle:Test:import.html.twig')
            ->getResponse()
        ;
    }
}
```

### export

- Api used for exporting data (requires [ImaticImportExportBundle](https://bitbucket.org/imatic/imatic-importexportbundle) bundle to be installed).
- class [ExportApi](/Controller/Api/Export/ExportApi.)
- methods:
    - `export`
        - arguments:
            - `$queryObject`
                - Query object of which result will be exported.
            - `$format`
                - Format in which the result will be exported (csv, xls, ...).
            - `$name`
                - Name of the file with exported data.
            - `$options`
                - `Exporter` options
                    - `displayCriteria`
                    - `serializationContext`
    - `filter`
    - `defaultSort`
    - `getResponse`
        - `BinaryFileResponse`

#### Example

```php
<?php

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserListQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller

/**
 * @Config\Route("/user")
 */
class UserController extends Controller
{
    use ContainerAwareTrait;
    use ApiTrait;

    /**
     * @Config\Route("/export")
     */
    public function exportAction()
    {
        return $this->export()
            ->export(new UserListQuery(), 'csv', 'users.csv')
            ->getResponse()
        ;
    }
}
```

