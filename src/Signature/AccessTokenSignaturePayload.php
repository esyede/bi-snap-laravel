<?php

namespace Esyede\BiSnap\Signature;

use Illuminate\Support\Facades\Log;
use Esyede\BiSnap\Config;
use Esyede\BiSnap\Timestamp;

class AccessTokenSignaturePayload
{
    protected $clientKey;
    protected $timestamp;

    /**
     * Constructor
     *
     * @param string $clientKey
     * @param string $timestamp
     */
    public function __construct($clientKey, $timestamp)
    {
        $this->clientKey = $clientKey;
        $this->timestamp = $timestamp;
    }

    /**
     * Stringify this object
     *
     * @return string
     */
    public function __toString()
    {
        $timestamp = (string) new Timestamp($this->timestamp);
        $stringToSign = "{$this->clientKey}|{$timestamp}";

        if (Config::instance()->isDebug()) {
            Log::debug(__CLASS__, ['string_to_sign' => $stringToSign]);
        }

        return $stringToSign;
    }
}
