Nastavení
=========
- v config.yml: imatic_resource/resources
- nastavení pro jednotlivé resources:
-- název resource
-- model class
-- controller class
-- template name, namespace
-- template var name
-- vlastní přesměrování
-- viz Sylius Resource a FOS Rest View

- podle nastavení se generují services / definují standard názvu pro:
-- controller
-- repository
-- form
-- handler

Flash message
=============
- helper pro formátování flash zpráv
- podle resource, akce a úspěšnosti
- dostane překladač, první zkusí přeložit celý string, pokud by někdo udělal zvláštní překlad, jinak zobrazí univerzální
- katalog překladu dle katalogu modelu

Testy
=====
- pro všechny komponenty a výchozí use case
- helper pro testování dalších use case
- podpora pro automatické testování generovaných služeb atp


obecne
  - prava

list
  - nacteni dat (mofifikace zakladni query => qb)
create
  - formular (typ)
  - handle (data)
edit
  - formular (typ)
  - handle (data)
show
  - nacteni dat (modifikace zakladni query)
delete
patch
  - handle (akce, seznam id|typ|filter)


List
----
- controller - infrastruktura
  - route (automaticky, nebo rucne)
  - template (pres view logiku)
- controller
  - vytvoreni zakladni query (data logika)
  - nacteni zaznamu (data logika)
    - filtr
    - pager
    - sorter
    - security
  - nacteni doplnujicich informaci (custom logika)
  - predani dat sablone (view logika)
  - rozhodnuti jaka sablona se ma pouzit (format, nazev)
- sablona
  - vypis radku
  - vypis ostatnich informaci (zahlavi, zapati)
  - akce radku
  - hromadne akce
  - filtr
  - sorter
  - pager
  - flashmessage (spis ne, to je v layoutu)

Show
----
- controller - infrastruktura
  - route
  - template
  - param converter
    - nacteni
    - security
- controller
  - nacteni doplnujicich informaci
  - predani dat sablone
  - rozhodnuti jaka sablona se ma pouzit (format, nazev)
- sablona
  - vypis zaznamu
  - vypis ostatnich informaci (zahlavi, zapati)
  - akce zaznamu (?)

Edit/Create
-----------
- controller - infrastruktura
  - route
  - template
  - param converter
    - nacteni
    - security
- controller
  - vytvoreni objektu (create)
  - vytvoreni formulare
  - bind, validace atp
    - OK
      - handle formulare
      - flashmessage
      - presmerovani resp sablona podle (api, web)
    - ELSE
      - flashmessage
  - predani dat sablone
  - rozhodnuti jaka sablona se ma pouzit (format, nazev)
- sablona
  - vypis formulare
  - vypis chyb formulare
  - akce formulare
  - flashmessage

Delete
------
- controller - infrastruktura
  - route
  - template
  - param converter
    - nacteni
    - security
...

Request
-------
- nacteni dat z requestu a prevedeni na nejake univerzalni struktury
  - filtrovani, strankovani, razeni
  - loadery, omezeni...
  => struktura s informacema o requestu

// read
- datova logika
  - vychozi query + parametry z requestu
  - aplikovani FSPL z requestu
  - aplikovani bezpecnostnich pravidel
  - donacteni vlastnich dat
  => struktura s daty pro view

    NAPAD:
    mohl by byt i obecny query objekt pro jednoduche/jednorazove query aby se dalo strankovat a radit i bez vlastni tridy atp..

    LIST:
    vytvorim instanci dotazu a predam konkretni data z requestu (sekce atp)
    predam data loaderu s dalsima request informacema ve forme constraintu
        - aplikuje FSPL
        - aplikuje security (JAK???)
        - nacte a vrati data (getQb, getData)

    ITEM:
    vytvorim instanci dotazu a predam konkretni data z requestu (sekce atp)
    predam data loaderu s dalsima request informacema ve forme constraintu
        - aplikuje L (loaders)
        - nactu a vratim data

- view logika
  - volba sablony a formatu, stavoveho kodu
  - data pro sablonu

    NAPAD:
    do view se muze predavat vice dat, ale jen jedna entita je vychozi
    - rozdil mezi JSON/XML (jedna entita) a template zobrazenim (muze zobrazovat i jina data..)
        - resit pres ViewHandler::prepareTemplateParameters!!:)

// update
- handle logika
  - ziskani entity (pokud neni empty_data option)
  - ziskani formu
  - bind formu
  - form je validni
    - handle
        - vraci data pro predani do view
            - create: novou hodnotu
            - update|pathOne: updavenou hodnotu
    - flash
        - podle standardu, univerzalni nebo vlastni (standard prekladu)
        - sluzba, vola se mimo handler, primo v ctrl
    - view
  - flash

- view logika
  - vlastni sluzba
  - podle kongigurace a requestu (DataConstraintSet::getRedirect)
  - zavolam render nebo redirect => vrati se response (s vyuzitim ViewHadneru a View)
  - volba sablony a formatu, stavoveho kodu
  - data pro sablonu
  // nebo
  - presmerovani podle parametru


Config
- name
- model class
- controller class
- template namespace
- template var
- custom redirects
-
- viz Sylius Resource a FOS Rest view...
 */


Resource
--------
- model (entity), inteface!!!, trait
- repository
- manager
- controller, api
- form, form handler
- voter
- template
- event?


data
- ziskani dat z requestu
  - filter, pager, sorter

- ziskavani dat
  - repository

- entity
  - model, interface

- security
  - voter
  - pravo

- rizeni, prezentace
  - controller + api
  - template + serializer

- prace s daty
  - manager
  - form
  - form handler

imatic_resource:
  resource:
    app_user:
      ctrl: class|service
      security:
        voter: service
        role:

<?php
class TestController
{
  /**
    * @Route()
    * @Secure()
    * @ParamConverter('Class', 'method')
    */
  public function indexAction($data)
  {
    return array('data' => $data);
  }

  public function index1Action($request)
  {
    $request = $this->get('request.processor')->list($request);
    $data = $this->get('data.loader')->load('app.user', $request);
    return $data;
  }

  public function showAction($id)
  {
    $data = $this->get('data.loader')->find('app.user', $id);
    return $data;
  }

  public function batchAction($request)
  {
    $request = $this->get('request.processor')->patch($request);
    $result = $batchExecutor->execute($request);
    return array('result' => $result);
  }

?>



Controller
- traity
- configurable
- this do helperu, nakonfiguruje misto fluent rozhrani (z instance ctrl se nacte i konfigurace, ctrl tam bude kvuli metodam, ktete controller obsahuje [pripadne muzu do konfigurace dat jen tridu kontrolleru])

Data sluzba
- nacita polozky i list
- updatuje a maze
- registruji se sluzby pro security/prava na objekty apod
- dalsi sluzby podle model trid
- pracuje s query objekty

Componenty
- registrace pro dane objekty
- napr pro user entitu pro list se zaregistruje komponenta a kdyz budu chtit vypsat list useru automaticky se pouzije
- resit na co nejnizsi urovni pro univerzalnost

Adresářová struktura
XXXXXXXXXXXXXXXXXXXX
- model (model interfaces)

    - query

        - select
        - update
        - delete
        - filter
        - sorter

    - security
    - command

        - formHandler
        - batchHandler
        - patchHandler

    - dalsi adresare

- data

    - data grid???

XXXXXXXXXXXXXXXXXXXX

controller
----------

- standardni reseni
- strategie pro vykresleni [OK]
- samostatnost (lze jen pridat routu)
- typehinting
- voters a param converters
- cache [OK]

component
---------

- viceurovnova konfigurace
- viceurovnovy kontext
- system sablon + base sablona
- pripravene primo pro dane pouziti (controller toho precijen resi vic)
- lze jednoduse resit system vice sablon na jedne strance [OK]

!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

nedat API k sobe podle typu a vlastnosti? viz:

zvazit, jestli to davat do namespace Contoller
-> mozna spis ano, aby to bylo stejne i v klientskych bundlech, ktere definuji vlastni API apod
-> nebo ControllerApi a ControllerFeature?

Controller/Api/Listing
- ListingApi
- ListingApiTrait

Controller/Feature/Template
- TemplateTrait
- Template
