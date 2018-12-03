<?php declare(strict_types=1);
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

    public function addCommandMessage(CommandResultInterface $commandResult)
    {
        foreach ($commandResult->getMessages() as $message) {
            $translated = $this->trans($message->getMessage(), $message->getParameters(), $message->getTranslationDomain());
            if ($message->getMessage() === $translated) {
                // Default translation for untranslated messages
                $this->session->getFlashBag()->add(
                    $message->getType(),
                    $this->trans($message->getText(), $message->getParameters(), 'ImaticDataBundleMessages')
                );
            } else {
                // Standard translation
                $this->add(
                    $message->getType(),
                    $message->getMessage(),
                    $message->getParameters(),
                    $message->getTranslationDomain()
                );
            }
        }
    }

    /**
     * @param string $type              (success|danger|warning|info)
     * @param string $message           command names or messages from handlers (user.create, user.delete, some message..)
     * @param array  $parameters        translation message parameters ([name = John, ...])
     * @param string $translationDomain
     */
    public function add($type, $message, array $parameters, $translationDomain)
    {
        $message = $this->trans($message, $parameters, $translationDomain);
        $this->session->getFlashBag()->add($type, $message);
    }

    private function trans($message, $parameters, $domain)
    {
        return $this->translator->trans($message, $parameters, $domain);
    }
}
