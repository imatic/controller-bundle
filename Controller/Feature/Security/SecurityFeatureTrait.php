<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Security;

/**
 * @property SecurityFeature $security
 */
trait SecurityFeatureTrait
{
    /**
     * Check for object in data feature.
     *
     * @param mixed $attributes
     * @param string $dataKey
     * @param \Closure $callback
     *
     * @return $this
     */
    public function addDataAuthorizationCheck($attributes, $dataKey = null, \Closure $callback = null)
    {
        if (null === $dataKey) {
            $dataKey = 'item';
        }

        $this->security->addDataCheck($attributes, $dataKey, $callback);

        return $this;
    }

    /**
     * @param bool $enable
     *
     * @return $this
     */
    public function enableDataAuthorization($enable = true)
    {
        $this->security->enableDataAuthorization($enable);

        return $this;
    }
}
