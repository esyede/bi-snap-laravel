<?php

namespace Esyede\BiSnap;

use Illuminate\Contracts\Support\Arrayable;

class Header implements Arrayable
{
    protected $contentType = 'application/json';
    protected $clientKey = null;
    protected $authorization = null;
    protected $authorizationCustomer = null;
    protected $timestamp = null;
    protected $signature = null;
    protected $origin = null;
    protected $partnerId = null;
    protected $externalId = null;
    protected $ipAddress = null;
    protected $deviceId = null;
    protected $latitude = null; // float, ex: 0.0005465
    protected $longitude = null; // float, ex: 0.887767
    protected $channelId = null;

    /**
     * Constructor
     *
     * @param string $contentType
     * @param string|null $clientKey
     * @param string|null $authorization
     * @param string|null $authorizationCustomer
     * @param string|null $timestamp
     * @param string|null $signature
     * @param string|null $origin
     * @param string|null $partnerId
     * @param string|null $externalId
     * @param string|null $ipAddress
     * @param string|null $deviceId
     * @param float|string|null $latitude
     * @param float|string|null $longitude
     * @param string|null $channelId
     */
    public function __construct(
        $contentType = 'application/json',
        $clientKey = null,
        $authorization = null,
        $authorizationCustomer = null,
        $timestamp = null,
        $signature = null,
        $origin = null,
        $partnerId = null,
        $externalId =  null,
        $ipAddress = null,
        $deviceId = null,
        $latitude = null,
        $longitude = null,
        $channelId = null
    ) {
        $this->contentType = $contentType;
        $this->clientKey = $clientKey;
        $this->authorization = $authorization;
        $this->authorizationCustomer = $authorizationCustomer;
        $this->timestamp = $timestamp;
        $this->signature = $signature;
        $this->origin = $origin;
        $this->partnerId = $partnerId;
        $this->externalId = $externalId;
        $this->ipAddress = $ipAddress;
        $this->deviceId = $deviceId;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->channelId = $channelId;
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'Content-Type' => $this->contentType,
            'Authorization' => $this->authorization,
            'Authorization-Customer' => $this->authorizationCustomer,
            'X-CLIENT-KEY' => $this->clientKey,
            'X-TIMESTAMP' => $this->timestamp,
            'X-SIGNATURE' => $this->signature,
            'X-ORIGIN' => $this->origin,
            'X-PARTNER-ID' => $this->partnerId,
            'X-EXTERNAL-ID' => $this->externalId,
            'X-IP-ADDRESS' => $this->ipAddress,
            'X-DEVICE-ID' => $this->deviceId,
            'X-LATITUDE' => $this->latitude,
            'X-LONGITUDE' => $this->longitude,
            'CHANNEL-ID' => $this->channelId,
        ];

        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Get only several header item
     *
     * @param array $keys
     *
     * @return array
     */
    public function only(array $keys)
    {
        $keys = array_map(function ($value) {
            return strtolower($value);
        }, $keys);

        $headers = $this->toArray();
        $attributes = [];

        foreach ($headers as $key => $value) {
            if (in_array(strtolower($key), $keys)) {
                $attributes[$key] = $value;
            }
        }

        return $attributes;
    }

    /**
     * Get necessary header items for requesting access token
     *
     * @return array
     */
    public function forGetAccessToken()
    {
        return $this->only(['content-type', 'x-client-key', 'x-timestamp', 'x-signature']);
    }
}
