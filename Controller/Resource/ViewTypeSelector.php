<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ViewTypeSelector
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var array
     */
    private $allowedTypes;

    /**
     * @var string
     */
    private $sessionKey;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        SessionInterface $session,
        array $types,
        $sessionKey
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->session = $session;
        $this->types = $types;

        $this->allowedTypes = [];
        foreach ($this->types as $typeName => $type) {
            if (empty($type['role']) || $this->authorizationChecker->isGranted($type['role'])) {
                $this->allowedTypes[$typeName] = $typeName;
            }
        }
        $this->sessionKey = $sessionKey;
    }

    /**
     * @return array
     */
    public function getAllowedTypes()
    {
        return $this->allowedTypes;
    }

    /**
     * @param array $types
     *
     * @return bool
     */
    public function isTypesAllowed(array $types)
    {
        return \count(\array_intersect($types, $this->getAllowedTypes())) > 0;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function isTypeAllowed($type)
    {
        return isset($this->allowedTypes[$type]);
    }

    /**
     * @param string $type
     *
     * @throws AccessDeniedException
     */
    public function ensureTypeAllowed($type)
    {
        if (!$this->isTypeAllowed($type)) {
            throw new AccessDeniedException(\sprintf('Access to "%s"  is not allowed', $type));
        }
    }

    /**
     * @return string|null
     */
    public function getDefaultType()
    {
        $sessionType = $this->getSessionType();
        if (\in_array($sessionType, $this->allowedTypes, true)) {
            return $sessionType;
        }
        return \reset($this->allowedTypes);
    }

    /**
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public function getDefaultTypeOrThrow()
    {
        $defaultType = $this->getDefaultType();
        if (null === $defaultType) {
            throw new NotFoundHttpException('Can not get default view type');
        }

        return $defaultType;
    }

    public function saveType($type)
    {
        $this->setSessionType($type);
    }

    protected function setSessionType($type)
    {
        $this->session->set($this->getTypeSessionKey(), $type);
    }

    protected function getSessionType()
    {
        return $this->session->get($this->getTypeSessionKey());
    }

    protected function getTypeSessionKey()
    {
        return \sprintf('imatic_controller_%s_type', $this->sessionKey);
    }
}
