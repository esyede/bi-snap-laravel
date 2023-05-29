<?php

namespace Esyede\BiSnap;

class HeaderFactory
{
    /**
     * Create Header class instance
     *
     * @param array $attributes
     *
     * @return Header
     */
    public static function make(array $attributes)
    {
        return new Header(
            data_get($attributes, 'content_type', 'application/json'),
            data_get($attributes, 'client_key'),
            data_get($attributes, 'authorization'),
            data_get($attributes, 'authorization_customer'),
            (string) new Timestamp(data_get($attributes, 'timestamp')),
            data_get($attributes, 'signature'),
            data_get($attributes, 'origin'),
            data_get($attributes, 'partner_id'),
            data_get($attributes, 'external_id'),
            data_get($attributes, 'ip_address'),
            data_get($attributes, 'device_id'),
            data_get($attributes, 'latitude'),
            data_get($attributes, 'longitude'),
            data_get($attributes, 'channel_id'),
        );
    }
}
