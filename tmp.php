<?php

class UserController
{
    /**
     * standardni zobrazeni vypisu
     */
    public function listAction(Request $request)
    {
        return $this->get('api')
            ->list('AppUserBundle:User')
            ->useRequest($request)// nepovinné
            ->getResponse();
    }

    /**
     * komponentove zobrazeni vypisu
     */
    public function listComponentAction(Request $request)
    {
        return $this->get('api')
            ->component('user.list')
            ->useRequest($request)// nepovinné
            ->getResponse();
    }

    /**
     * Standardni zobrazeni dat
     */
    public function showAction($id)
    {
        return $this->get('api')
            ->show('AppUserBundle:User', $id)

            ->setTemplate('AppUserBundle:User:detail.html.twig')
            ->setTemplateVariableName('user')
            ->addTemplateVariable('group', new QueryObject())

            ->getResponse();
    }

    /**
     * Zobrazeni komponenty
     */
    public function componentAction($id)
    {
        return $this->get('api')
            ->component('user.show', $config = [], $context = [])
            ->getResponse();
    }

    public function editAction($id)
    {
        return $this->get('api')
            ->edit('AppUserBundle:User', $id)
            ->form('user.form')
            ->successRedirect('show', ['id' => $id])
            ->getResponse();
    }

    public function editCommandAction($id)
    {
        return $this->get('api')
            ->edit('AppUserBundle:User', $id)
            ->edit(new UserQuery($id))
            ->edit($user)
            ->form('user.form')
            ->commandName('user.update')
            ->successRedirect('show', ['id' => $id])
            ->getResponse();
    }

    public function deleteAction($id)
    {
        return $this->get('api')
            ->delete('AppUserBundle:User', $id)
            ->commandName('user.delete')
            ->successRedirect('list')
            ->getResponse();
    }

    public function patchAction($id)
    {
        return $this->get('api')
            ->patch('AppUserBundle:User', $id)
            ->commandName('user.activate')
            ->successRedirect('show')
            ->getResponse();
    }

    public function apiAction(Request $request)
    {
        return $this->get('api')
            ->api('???')
            ->commandName('api.action')
            ->successRedirect('list')
            ->getResponse();
    }

    public function batchAction(Request $request)
    {
        return $this->get('api')
            ->batch()
            ->commandNames(['action1', 'action2'])
            ->useRequest($request)
            ->getResponse();
    }
}