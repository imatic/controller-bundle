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
    use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Form\Type\UserType;
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
                ->batchCommand('user.greet.batch')
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
* command handleru se dále předá parametr "selected" z requestu (např idčka uživatelů, se kterými chceme něco udělat)
* je možné specifikovat více commandů (např. ["greet" => "command"])
    * který command se provede se rozhodne podle request parametrů (pro náš command tedy hledáme requestu parametr "greet" => "command")

dataAction
``````````

* příklad download API
* v našem příkladu stáhne soubor userData
* je též možné předat jako argument SingleResultQueryObjectInterface který vrací FileObjectInterface

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


CRUD
----

**WIP**

* controller
    * generuje se automaticky podle nastavení
    * chová se podle nastavení
    * generují se pouze definované controllery
    * je možné definovat stejný typ (list) controlleru vícekrát
* routes
    * generuje se automaticky podle nastavení
* security
    * práva se řeší přes votery u metod, které pracují s objektem
    * práva se řeší přes role u metod, které pracují s kolekcí objektů
* templates
    * výchozí šablony pro list, edit, create a show poskytuje ImaticViewBundle
    * šablony lze v nastavení předefinovat
* entities
    * řeší se standardně, pouze se nastavuje pro definici data_class atp
* forms
    * řeší se standardně, pouze se nastavuje form type pro create a edit
* query
    * řeší se standardně, pouze se nastavuje form type pro list, create, edit a delete
* handler
    * výchozí handlery pro create, edit a delete poskytuje ImaticDataBundle
    * handlery je možné v nastavení předefinovat
* query
    * řeší se standardně, pouze se nastavuje query object pro list, create, edit a delete (a autocomplete)
* translations
    * v nastavení se definuje slovník pro překlady v šablonách

Dořešit
```````
* nastavení práv
    * collection akce: role
    * item akce: role nebo voter
* názvy entit
    * nazev entity, default trida entity?
* nastavení překladových slovníků
    * nazev slovniku, default dle resource podle konvence?
* nastavení akcí (row, batch, page)
    * výchozí akce v defaults
    * nastavení jako mají ActionOptions, Configuration nevaliduje, byla by to duplicita
    * rozdělení jako v šabloně, page, batch, row
    * pak dle resource
    * v šabloně se pak vypíší, všechny dostupné pro daný resource a typ mimo aktuální (edit nezobrazuje edit akci)

Jako config používám jen config pro akce resource, jak dostanu config page atp akcí do nastavení, jsou per resource?
Obecně, nastavení per resource bude více!
