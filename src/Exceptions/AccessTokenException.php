<?php

namespace Esyede\BiSnap\Exceptions;

use Exception;

class AccessTokenException extends Exception
{
    protected $provider;

    /**
     * Constructor
     *
     * @param string $provider
     * @param string|null $message
     */
    public function __construct($provider, $message = null)
    {
        $this->provider = $provider;
        parent::__construct($message ? $message : 'Access token not defined or has expired');
    }

    /**
     * Get provider
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }
}
