<?php

namespace Esyede\BiSnap\Signature;

class ServiceSignature
{
    /**
     * Generate symmetric signature for request a service
     *
     * @param string $clientSecret
     * @param ServiceSignatureData $data
     *
     * @return string
     */
    public static function symmetric($clientSecret, ServiceSignaturePayload $data)
    {
        return base64_encode(hash_hmac('sha512', (string) $data, $clientSecret, true));
    }

    /**
     * Verify signature
     *
     * @param ServiceSignaturePayload $data
     * @param string $signature
     * @param string $clientSecret
     *
     * @return bool
     */
    public static function symmetricVerify(ServiceSignaturePayload $data, $signature, $clientSecret)
    {
        return hash_equals($signature, static::symmetric($clientSecret, $data));
    }
}
