<?php

declare(strict_types=1);

namespace Esyede\BiSnap;

class Config
{
    protected $privateKey = null;
    protected $provider = null;

    protected static $instance = null;
    protected static $debug = false;

    /**
     * Constructor
     *
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        $this->privateKey = new PrivateKey($name);
        $this->provider = new Provider($name);
    }

    /**
     * Get private key
     *
     * @return PrivateKey|null
     */
    public function privateKey()
    {
        return $this->privateKey;
    }

    /**
     * Get provider
     *
     * @return Provider|null
     */
    public function provider()
    {
        return $this->provider;
    }

    /**
     * Get provider log channel
     *
     * @return string|null
     */
    public function logChannel()
    {
        return $this->provider->logChannel();
    }

    /**
     * Is debug mode activated?
     *
     * @return bool
     */
    public function isDebug()
    {
        return (bool) static::$debug;
    }

    /**
     * Get config instance
     *
     * @return static|null
     */
    public static function instance()
    {
        return static::$instance;
    }

    /**
     * Create singleton instance.
     *
     * @param string $name
     * @return static|null
     */
    public static function load($name)
    {
        return static::$instance = new static($name);
    }

    /**
     * Activate debug mode
     *
     * @param bool $isDebug
     *
     * @return void
     */
    public static function debug($isDebug)
    {
        static::$debug = (bool) $isDebug;
    }
}
