<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Security;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SecurityFeature
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var array
     */
    private $dataAuthorizationChecks;

    /**
     * @var bool
     */
    private $dataAuthorizationEnabled;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->dataAuthorizationChecks = [];
        $this->dataAuthorizationEnabled = false;
    }

    public function enableDataAuthorization($enable = true)
    {
        $this->dataAuthorizationEnabled = $enable;
    }

    public function addDataCheck($attributes, $dataKey, \Closure $callback = null)
    {
        $this->dataAuthorizationEnabled = true;

        $this->dataAuthorizationChecks[] = [
            'attributes' => $attributes,
            'dataKey' => $dataKey,
            'callback' => $callback,
        ];
    }

    public function checkDataAuthorization(array $data)
    {
        if (false === $this->dataAuthorizationEnabled) {
            return;
        }

        foreach ($this->dataAuthorizationChecks as $check) {
            $dataKey = $check['dataKey'];

            if (!isset($data[$dataKey])) {
                throw new \InvalidArgumentException(\sprintf('Invalid data key "%s"', $dataKey));
            }

            $dataItem = $data[$dataKey];

            if ($check['callback']) {
                $dataItem = \call_user_func($check['callback'], $dataItem);
            }

            $message = \sprintf(
                'Access denied for data with key "%s" (attributes: "%s")',
                $dataKey,
                \var_export($check['attributes'], true)
            );
            $this->checkAuthorization($check['attributes'], $dataItem, $message);
        }
    }

    /**
     * @param mixed $attributes
     * @param mixed $object
     *
     * @return bool
     */
    public function isGranted($attributes, $object = null)
    {
        return $this->authorizationChecker->isGranted($attributes, $object);
    }

    /**
     * @param mixed $attributes
     * @param mixed $object
     * @param string $message
     *
     * @throws AccessDeniedException
     */
    public function checkAuthorization($attributes, $object = null, $message = 'Access Denied.')
    {
        if (!$this->isGranted($attributes, $object)) {
            $exception = new AccessDeniedException($message);
            $exception->setAttributes($attributes);
            $exception->setSubject($object);

            throw $exception;
        }
    }
}
