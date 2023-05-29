<?php

namespace Esyede\BiSnap\IntrabankTransfer;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Esyede\BiSnap\AccessToken;
use Esyede\BiSnap\Client;
use Esyede\BiSnap\Config;
use Esyede\BiSnap\Contracts\ServicePayload;
use Esyede\BiSnap\HeaderFactory;
use Esyede\BiSnap\Signature\ServiceSignature;
use Esyede\BiSnap\Signature\ServiceSignaturePayload;

class Transfer
{
    public $endpoint = '/v1.0/transfer-intrabank';

    protected $config = null;
    protected $client;
    protected $accessToken = null;

    protected $origin;
    protected $channelId;
    protected $externalId;
    protected $payload = null;
    protected $timestamp = null;

    /**
     * Constructor
     *
     * @param string $origin
     * @param string $channelId
     * @param string $externalId
     * @param ServicePayload|null $payload
     * @param string|null $timestamp
     */
    public function __construct(
        $origin,
        $channelId,
        $externalId,
        ServicePayload $payload = null,
        $timestamp = null
    ) {
        $this->config = Config::instance();
        $this->client = new Client();
        $this->accessToken = AccessToken::get($this->config->provider()->name());
        $this->timestamp = $timestamp ? $timestamp : time();

        $this->origin = $origin;
        $this->channelId = $channelId;
        $this->externalId = $externalId;
        $this->payload = $payload;
        $this->timestamp = $timestamp;
    }

    /**
     * Send transfer request
     *
     * @param Payload|null $payload
     *
     * @return Response
     */
    public function send(ServicePayload $payload = null)
    {
        if ($payload) {
            $this->payload = $payload;
        }

        return $this->client->withHeaders($this->headers())->post(
            $this->config->provider()->serviceUrl($this->endpoint),
            $this->payload->toArray()
        );
    }

    /**
     * Generate transfer signature
     *
     * @return string
     */
    private function signature()
    {
        $serviceSignaturePayload = new ServiceSignaturePayload(
            'POST',
            $this->config->provider()->relativePath($this->endpoint),
            (string) $this->accessToken,
            $this->timestamp,
            $this->payload->toArray(),
        );

        return ServiceSignature::symmetric(
            $this->config->provider()->clientSecret(),
            $serviceSignaturePayload
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
            'authorization' => 'Bearer ' . ((string) $this->accessToken),
            'timestamp' => $this->timestamp,
            'signature' => $this->signature(),
            'partner_id' => $this->config->provider()->partnerId(),
            'origin' => $this->origin,
            'external_id' => (string) $this->externalId,
            'channel_id' => (string) $this->channelId,
        ])->toArray();
    }
}
