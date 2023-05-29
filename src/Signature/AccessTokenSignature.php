<?php

namespace Esyede\BiSnap\Signature;

use Exception;
use InvalidArgumentException;

class AccessTokenSignature
{
    /**
     * Generate asymmetric signature for request a access token
     *
     * @param string $privateKey
     * @param string|int $timestamp
     *
     * @return string
     */
    public static function asymmetric($privateKey, AccessTokenSignaturePayload $data)
    {
        $asymmetricKey = openssl_pkey_get_private($privateKey);

        if (!$asymmetricKey) {
            throw new InvalidArgumentException('Invalid private key.');
        }

        $signature = null;

        openssl_sign((string) $data, $signature, $asymmetricKey, 'RSA-SHA256');

        if (!$signature) {
            throw new Exception('Failed to generate signature.');
        }

        return base64_encode($signature);
    }

    /**
     * Verify signature
     *
     * @param AccessTokenSignatureData $data
     * @param string $signature
     * @param string $publicKey
     *
     * @return bool
     */
    public static function asymmetricVerify(AccessTokenSignaturePayload $data, $signature, $publicKey)
    {
        $asymmetricKey = openssl_pkey_get_public($publicKey);
        return openssl_verify((string) $data, base64_decode($signature), $asymmetricKey, 'RSA-SHA256');
    }
}
