# Yaml interface

This interface allows you to specify all actions using yaml. It is using [fluent interface](FluentInterface.md) in the background. If you're writing your own action, you can use one of the [predefined controllers](/var/www/html/imatic/imatic-controllerbundle/Controller/Resource).

All of the predefined controllers are passing variables `resource` ([Resource](/Resource/Config/Resource.php)) and `action` ([ResourceAction](/Resource/Config/ResourceAction.php)) into templates. When implementing your own controller, you can use [ResourceController](/Controller/Resource/ResourceController.php) as a parent to have access to some basic functionality.

```shell
$ ./bin/console config:dump-reference imatic_controller
```

```yaml
imatic_controller:
    resources_config:

        # Default values for resource config. Default values for actions are indexed by action type.
        prototype:

            # Generic config usable by all actions.
            config:

                # Specification of generated route.
                route:
                    name:                 ~
                    path:                 ~
                    methods:              []
                entity:               ~ # Required

                # Role to check before working with resource.
                role:                 ~

                # Form for the resource
                form:                 ~

                # Flag if roles should be checked.
                data_authorization:   false
                translation_domain:   ~
                name:                 ~ # Required

                # Fields of the resource passed into templates in imatic/view-bundle.
                fields:               ~
                query:

                    # Query object for action of type "list".
                    list:                 ~

                    # Query object for actions using single resource object. It expects first argument to be id of the object and second class of the object.
                    item:                 ~

                # Extra configuration which can be used e.g. in templates.
                extra:                []
            actions:

                # Prototype
                -

                    # Specification of generated route.
                    route:
                        name:                 ~
                        path:                 ~
                        methods:              []

                    # Query object for the action.
                    query:                ~

                    # Template used for rendering the action.
                    template:             ~

                    # Type of the action (merges prototype action with name of the "type" with this action).
                    type:                 ~ # One of "list"; "autocomplete"; "show"; "edit"; "create"; "delete"; "batch"

                    # Group of the action. In case "query" is not specified, the one from "config.query.$group is used".
                    group:                ~ # One of "list"; "item"; "batch"; "api"

                    # Specification of controller action to execute.
                    controller:           ~
                    form:                 ~
                    form_options:         []
                    command:              ~
                    command_parameters:   []
                    redirect:             ~

                    # Role required to perform the action.
                    role:                 ~

                    # This action refers to another action. Format is: "resource:action"
                    target:               ~
                    filter:               ~
                    data_authorization:   ~

                    # Fields of the resource passed into templates in imatic/view-bundle.
                    fields:               ~

                    # Extra configuration which can be used e.g. in templates.
                    extra:                []
    resources:

        # Prototype
        -

            # Generic config usable by all actions.
            config:

                # Specification of generated route.
                route:
                    name:                 ~
                    path:                 ~
                    methods:              []
                entity:               ~ # Required

                # Role to check before working with resource.
                role:                 ~

                # Form for the resource
                form:                 ~

                # Flag if roles should be checked.
                data_authorization:   false
                translation_domain:   ~
                name:                 ~ # Required

                # Fields of the resource passed into templates in imatic/view-bundle.
                fields:               ~
                query:

                    # Query object for action of type "list".
                    list:                 ~

                    # Query object for actions using single resource object. It expects first argument to be id of the object and second class of the object.
                    item:                 ~

                # Extra configuration which can be used e.g. in templates.
                extra:                []
            actions:

                # Prototype
                -

                    # Specification of generated route.
                    route:
                        name:                 ~
                        path:                 ~
                        methods:              []

                    # Query object for the action.
                    query:                ~

                    # Template used for rendering the action.
                    template:             ~

                    # Type of the action (merges prototype action with name of the "type" with this action).
                    type:                 ~ # One of "list"; "autocomplete"; "show"; "edit"; "create"; "delete"; "batch"

                    # Group of the action. In case "query" is not specified, the one from "config.query.$group is used".
                    group:                ~ # One of "list"; "item"; "batch"; "api"

                    # Specification of controller action to execute.
                    controller:           ~
                    form:                 ~
                    form_options:         []
                    command:              ~
                    command_parameters:   []
                    redirect:             ~

                    # Role required to perform the action.
                    role:                 ~

                    # This action refers to another action. Format is: "resource:action"
                    target:               ~
                    filter:               ~
                    data_authorization:   ~

                    # Fields of the resource passed into templates in imatic/view-bundle.
                    fields:               ~

                    # Extra configuration which can be used e.g. in templates.
```


## `imatic_controller.resources_config.prototype`

- Default values for user defined actions/config.
- You can include [config.yml](/Resources/config/config.yml) into your project to get some defaults.
  ```yaml
  imports:
      - { resource: '@ImaticControllerBundle/Resources/config/config.yml' }
  ```
## `imatic_controller.resources`

- Actual resources configuration.

### Example of creating resource with actions using default values from previously imported config

```yaml
imatic_controller:
    resources:
        app_user:
            config:
                route: { path: /user }
                entity: User
                query:
                    list: UserListQuery
                    item: UserQuery
                fields:
                    - { name: name, format: text }
                    - { name: age, format: number }
                name: user
                form: UserType
            actions:
                list: ~
                create: ~
                show: ~
                edit: ~
                delete: ~
```

### Viewing final configuration of resources

- You can run command `imatic:controller:resource-debug` to see available resources.
  ```shell
  $ ./bin/console imatic:controller:resource-debug
  ```
- Adding optional argument `resource` will show final config of resource with list of actions.
- Adding additional optional argument `action` will show final config of the action of the resource.

