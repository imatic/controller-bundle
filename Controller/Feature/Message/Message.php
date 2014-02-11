<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Message
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(SessionInterface $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    /*
     * Vyresit konvence pro preklady
     * - controller action
     * - command result
     * - jine zpravy
     *
     * Jedna moznost
     * - ImaticUserBundle:User
     * - edit
     * - success
     *
     * Dalsi moznost
     * - user.edit
     * - success
     */

    public function add($type, $message, array $parameters = [])
    {

    }
}