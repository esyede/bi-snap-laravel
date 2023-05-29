<?php

namespace Esyede\BiSnap;

class Provider
{
    /**
     * The rovider name
     *
     * @var string|null
     */
    protected $name = null;

    /**
     * The partner id
     */
    protected $partnerId = null;

    /**
     * The provider client key
     *
     * @var string|null
     */
    protected $clientKey = null;

    /**
     * The provider client secret
     *
     * @var string|null
     */
    protected $clientSecret = null;

    /**
     * The provider base url
     *
     * @var string|null
     */
    protected $baseUrl = null;

    /**
     * The provider api prefix
     *
     * @var string|null
     */
    protected $apiPrefix = null;

    /**
     * Get log channel
     *
     * @var string|null
     */
    protected $logChannel = null;

    /**
     * Constructor
     *
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        if ($name) {
            $this->load($name);
        }
    }

    /**
     * Load provider config
     *
     * @param string $name
     *
     * @return void
     */
    public function load($name)
    {
        $this->name = strtolower($name);

        $config = config("snap.providers.{$this->name}");

        $this->partnerId = data_get($config, 'partner_id');
        $this->clientKey = data_get($config, 'client_key');
        $this->clientSecret = data_get($config, 'client_secret');
        $this->baseUrl = data_get($config, 'host');
        $this->apiPrefix = data_get($config, 'api_prefix');
        $this->logChannel = data_get($config, 'log_channel');
    }

    /**
     * Get provider name
     *
     * @return string|null
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Get partner id
     *
     * @return string|null
     */
    public function partnerId()
    {
        return $this->partnerId;
    }

    /**
     * Get provider client key
     *
     * @return string|null
     */
    public function clientKey()
    {
        return $this->clientKey;
    }

    /**
     * Get provider client secret
     *
     * @return string|null
     */
    public function clientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Get provider base url
     *
     * @return string|null
     */
    public function baseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Get relative path
     *
     * @param string $endpoint
     *
     * @return string
     */
    public function relativePath($endpoint)
    {
        return $this->apiPrefix . $endpoint;
    }

    /**
     * Get service url
     *
     * @param string $endpoint
     *
     * @return string
     */
    public function serviceUrl($endpoint)
    {
        return $this->baseUrl() . $this->relativePath($endpoint);
    }

    /**
     * Get log channel
     *
     * @return string|null
     */
    public function logChannel()
    {
        return $this->logChannel;
    }
}
