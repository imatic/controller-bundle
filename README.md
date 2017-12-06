[![Build Status](https://secure.travis-ci.org/imatic/controller-bundle.png?branch=master)](http://travis-ci.org/imatic/controller-bundle)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

# ImaticControllerBundle

This bundle makes it easy to write simple controllers for all kinds of actions. It heavilly uses [ImaticDataBundle](https://github.com/imatic/data-bundle). So you you should read it's documentation first if you didn't yet.

The bundle allows you to write simple actions in 2 forms

- using fluent interface

```php
<?php

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Config\Route("/user")
 */
class UserController extends Controller
{
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

- using yaml

```yaml
imatic_controller:
    resources:
        app_user_list:
            config:
                route: { path: /user }
                entity: User
                query:
                    list: UserListQuery
                fields:
                    - { name: name, format: text }
                    - { age: age, format: number }
            actions:
                list: ~
```

Further reading
---------------

- Visit our [documentation](Resources/doc/README.md) to learn about all features of this bundle.

