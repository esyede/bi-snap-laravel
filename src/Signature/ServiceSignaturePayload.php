<?php

namespace Esyede\BiSnap\Signature;

use Illuminate\Support\Facades\Log;
use Esyede\BiSnap\Config;
use Esyede\BiSnap\Timestamp;

class ServiceSignaturePayload
{
    protected $httpMethod;
    protected $endpointUrl;
    protected $accessToken;
    protected $timestamp;
    protected $payload;

    /**
     * Create signature payload
     *
     * @param string $httpMethod
     * @param string $endpointUrl
     * @param string $accessToken
     * @param string $timestamp
     * @param string|array $payload
     */
    public function __construct($httpMethod, $endpointUrl, $accessToken, $timestamp, $payload = '')
    {
        $this->httpMethod = $httpMethod;
        $this->endpointUrl = $endpointUrl;
        $this->accessToken = $accessToken;
        $this->timestamp = $timestamp;
        $this->payload = $payload;
    }

    /**
     * Stringify this object
     *
     * @return string
     */
    public function __toString()
    {
        $json = is_array($this->payload) ? json_encode($this->payload) : (string) $this->payload;
        $minified = hash('sha256', $json);
        $values = [
            $this->httpMethod,
            $this->endpointUrl,
            $this->accessToken,
            $minified,
            (string) new Timestamp($this->timestamp),
        ];

        $stringToSign = implode(':', $values);

        if (Config::instance()->isDebug()) {
            Log::debug(__CLASS__, ['json' => $json, 'string_to_sign' => $stringToSign]);
        }

        return $stringToSign;
    }
}
