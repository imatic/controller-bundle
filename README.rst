ImaticControllerBundle
======================

**Použití fluent controller API usnadnění a sjednocení práce kterou provádí controller.**

Bohužel se často se stává, že controller obsahuje business logiku, nebo duplicitní kód což není správně.
Controller API sjednocuje kód a umožňuje psát logiku pro controller velmi jednoduše a přitom správně a robustně.

Controller API využívá funkcionality `DataBundle <https://bitbucket.org/imatic/imatic-databundle>`_, je vhodné si první přečíst jeho dokumentaci.

Použití
-------

.. sourcecode:: php

    <?php
    namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Controller;

    use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
    use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserListQuery;
    use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserQuery;
    use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Entity\User;
    use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
    use Symfony\Component\DependencyInjection\ContainerAwareInterface;
    use Symfony\Component\DependencyInjection\ContainerAwareTrait;

    /**
     * @Config\Route("/user")
     */
    class UserController implements ContainerAwareInterface
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

        /**
         * @Config\Route("/edit/{id}", name="app_user_edit")
         * @Config\Method({"GET", "POST"})
         */
        public function editAction($id)
        {
            return $this
                ->form('app_imatic_controller_user')
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
                ->form('app_imatic_controller_user', new User())
                ->commandName('user.create')
                ->successRedirect('app_user_edit', function (CommandResultInterface $result, User $user) {
                    return ['id' => $user->getId()];
                })
                ->setTemplateName('AppImaticControllerBundle:Test:edit.html.twig')
                ->getResponse();
        }

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

        /**
         * @Config\Route("/greet/{username}")
         * @Config\Method("GET")
         */
        public function greetAction($username)
        {
            return $this->command()
                ->command('user.greet', [
                    'username' => $username,
                ])
                ->redirect('app_user_list')
                ->getResponse()
            ;
        }

        /**
         * @Config\Route("/greet-batch")
         */
        public function greetBatchAction()
        {
            return $this
                ->batchCommand([
                    'greet' => 'user.greet.batch',
                ])
                ->redirect('app_user_list')
                ->getResponse();
        }

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

autoCompleteAction
``````````````````

* příklad použítí autocomplete API
* vrátí v jsonu s klíči "id" a "text" seznam možných výsledků
* pro vlastní hodnotu klíče id nebo text lze přepsat closures identifierFunction/labelFunction, které se předají jako parametr stejnojmenným metodám

showAction
``````````

* příklad použití show API

listAction
``````````

* příklad použití listing API
* navíc je možné předat vlastní filter/sorter/pager (displayCriteria viz DataBundle), pokud se nepředá, je vytvořen automaticky

editAction
``````````

* příklad použití form API
* při volání getResponse se data z requestu uloží do formu a poté se zavolá command

createAction
````````````

* obdobný příklad jako ten předchozí

deleteAction
````````````

* příklad na command API

activateAction
``````````````

* příklad na object command API
* oproti command API se u object command API předá query objekt. Výsledný objekt se pak handleru předá jako 'object' parametr

greetAction
```````````

* další príklad na command API

greetBatchAction
````````````````

* příklad na batch command API
* je možné specifikovat více commandů
* který command se provede se rozhodne podle request parametrů (pro náš command tedy hledáme requestu parametr "greet" => "command")
* command handleru se dále předá parametr "selected" z requestu (např idčka uživatelů, se kterými chceme něco udělat)

dataAction
``````````

* příklad download API
* v našem příkladu stáhne soubor userData

Podrobnosti
-----------

* detaily jednotlivých příkladů (jak vypadají commandy např.) lze nalézt v testovacím projektu, který je v adresáři ``Tests/Fixtures/TestProject/``
* příklady requestů lze zase najít ve funkčních testech pro jednotlivé APIs

TODO
----

- security & permissions
- debug mode
- todo in code
- default templates and template names (listing, show, edit, create)