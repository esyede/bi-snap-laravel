<?php

namespace Esyede\BiSnap;

use Illuminate\Support\Facades\Cache;
use Esyede\BiSnap\Auth\AccessToken as AuthAccessToken;
use Esyede\BiSnap\Exceptions\AccessTokenException;

class AccessToken
{
    protected $name;

    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get cache name
     *
     * @return string
     */
    protected function cacheKey()
    {
        return "snap.{$this->name}.access_token";
    }

    /**
     * Store access token payload to cache
     *
     * @return void
     */
    protected function cache(array $payload)
    {
        Cache::put($this->cacheKey(), $payload, data_get($payload, 'expiresIn', 600));
    }

    /**
     * Put access token payload to cache
     *
     * @return void
     */
    public static function put($name, array $payload)
    {
        (new static($name))->cache($payload);
    }

    /**
     * Get access token instance
     *
     * @param string $name
     *
     * @return static
     */
    public static function get($name)
    {
        $token = new static($name);

        if (!Cache::has($token->cacheKey())) {
            (new AuthAccessToken())->get();
        }

        return $token;
    }

    public function __toString()
    {
        $payload = Cache::get($this->cacheKey());

        if (!$payload) {
            throw new AccessTokenException($this->name);
        }

        return data_get($payload, 'accessToken');
    }
}
