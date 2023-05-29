<?php

namespace Esyede\BiSnap\Auth;

use Illuminate\Http\Client\Response;
use Esyede\BiSnap\AccessToken as Token;
use Esyede\BiSnap\Client;
use Esyede\BiSnap\Config;
use Esyede\BiSnap\HeaderFactory;
use Esyede\BiSnap\Signature\AccessTokenSignature;
use Esyede\BiSnap\Signature\AccessTokenSignaturePayload;

class AccessToken
{
    public $endpoint = '/v1.0/access-token/b2b';

    protected $config = null;
    protected $client;
    protected $timestamp;
    protected $cache = true;

    /**
     * Constructor
     *
     * @param string|null $timestamp
     * @param bool $cache
     */
    public function __construct($timestamp = null, $cache = true)
    {
        $this->config = Config::instance();
        $this->client = new Client();
        $this->timestamp = $timestamp ? $timestamp : time();
        $this->cache = (bool) $cache;
    }

    /**
     * Get access token to provider
     *
     * @return Response
     */
    public function get()
    {
        $response = $this->client->withHeaders($this->headers())->post(
            $this->config->provider()->serviceUrl($this->endpoint),
            ['grantType' => 'client_credentials']
        );

        if ($response->ok() && $this->cache) {
            Token::put($this->config->provider()->name(), $response->json());
        }

        return $response;
    }

    /**
     * Generate access token signature
     *
     * @return string
     */
    private function signature()
    {
        $accessTokenSignaturePayload = new AccessTokenSignaturePayload(
            $this->config->provider()->clientKey(),
            $this->timestamp
        );

        return AccessTokenSignature::asymmetric(
            (string) $this->config->privateKey(),
            $accessTokenSignaturePayload
        );
    }

    /**
     * Generate request headers
     *
     * @return array
     */
    private function headers()
    {
        return HeaderFactory::make([
            'client_key' => $this->config->provider()->clientKey(),
            'timestamp' => $this->timestamp,
            'signature' => $this->signature(),
        ])->forGetAccessToken();
    }
}
