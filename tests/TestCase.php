<?php

namespace Esyede\BiSnap\Tests;

use Illuminate\Contracts\Config\Repository;
use Orchestra\Testbench\TestCase as Base;
use phpseclib3\Crypt\RSA;

abstract class TestCase extends Base
{
    const KEYS = __DIR__ . '/keys';
    const PUBLIC_KEY = self::KEYS . '/snap_test_public.key';
    const PRIVATE_KEY = self::KEYS . '/snap_test_private.key';

    protected function setUp(): void
    {
        parent::setUp();

        $key = RSA::createKey(4096);

        file_put_contents(self::PUBLIC_KEY, (string) $key->getPublicKey());
        file_put_contents(self::PRIVATE_KEY, (string) $key);
    }

    protected function getEnvironmentSetUp($app)
    {
        // Bisa diganti dengan config biasa di laravel
        $config = $app->make(Repository::class);
        $config->set('snap.providers.test', [
            'host' => 'http://bi-snap-laravel.test',
            'client_key' => '1176e4b7-4778-45c2-8044-630c5e7a392e',
            'client_secret' => 'IvrRfBAuQO3lDD8bfMqOslYcrwgFhI0z',
            'partner_id' => 'TEST',
            'public_key' => self::PUBLIC_KEY,
            'private_key' => self::PRIVATE_KEY,
            'log_channel' => 'daily',
            'api_prefix' => '/api-test',
        ]);
    }
}
