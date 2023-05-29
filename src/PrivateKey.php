<?php

namespace Esyede\BiSnap;

class PrivateKey
{
    /**
     * Private key name
     *
     * @var string|null
     */
    protected $name = null;

    /**
     * Private key path
     *
     * @var string|null
     */
    protected $path = null;

    /**
     * Constructor
     *
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        if ($name) {
            $this->name = strtolower($name);
            $path = config("snap.providers.{$this->name}.private_key");
            $this->path = $path ? $path : Config::get('snap.private_key');

            if (!preg_match('/^\//', $this->path)) {
                $this->path = storage_path($this->path);
            }
        }
    }

    /**
     * Get path of private key
     *
     * @return string|null
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * Stringify this object
     *
     * @return string
     */
    public function __toString()
    {
        return file_get_contents($this->path);
    }
}
