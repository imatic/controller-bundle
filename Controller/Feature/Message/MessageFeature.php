<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Message;

use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class MessageFeature
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(Session $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    public function addCommandMessage($bundle, $commandName, CommandResultInterface $commandResult)
    {
        // Result messages
        if ($commandResult->hasMessages()) {
            foreach ($commandResult->getMessages() as $message) {
                $messageText = sprintf('%s.%s', $commandName, $message->getText());
                $this->add($message->getType(), $bundle, $messageText, $message->getParameters());
            }
        } else {
            // Standard message
            $type = $commandResult->isSuccessful() ? 'success' : 'error';
            $messageText = sprintf('%s.%s', $commandName, $type);
            $this->add($type, $bundle, $messageText);
        }
    }

    /**
     * Catalog/Domain = $BundleName.Messages
     *
     * @param string $type (success|danger|warning|info)
     * @param string $bundle (AppUserBundle)
     * @param string $message command names or messages from handlers (user.create, user.delete, some message..)
     * @param array $parameters translation message parameters ([name = John, ...])
     */
    public function add($type, $bundle, $message, array $parameters = [])
    {
        $domain = sprintf('%sMessages', $bundle);
        $message = $this->translator->trans($message, $parameters, $domain);
        $this->session->getFlashBag()->add($type, $message);
    }
}
