<?php

namespace Esyede\BiSnap\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Esyede\BiSnap\AccessToken;
use Esyede\BiSnap\Auth\AccessToken as AuthAccessToken;
use Esyede\BiSnap\Config;
use Esyede\BiSnap\Tests\TestCase;

class GetAccessTokenTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'http://bi-snap-laravel.test/api-test/v1.0/access-token/b2b' => Http::response([
                'responseCode' => '2007300',
                'responseMessage' => 'Sucessful',
                'accessToken' => 'nd8vzUzOHlbKf82Hcn5VP22SdO56WKAoQC7mExbTfd68tPBzQ84Ocv',
                'tokenType' => 'Bearer',
                'expiresIn' => '900',
            ]),
        ]);
    }

    public function testGettingAcessToken()
    {
        Config::load('test');

        $response = (new AuthAccessToken())->get();

        $this->assertTrue($response->status() == 200);
        $this->assertArrayHasKey('responseCode', $response->json());
        $this->assertArrayHasKey('responseMessage', $response->json());
        $this->assertArrayHasKey('accessToken', $response->json());
        $this->assertArrayHasKey('tokenType', $response->json());
        $this->assertArrayHasKey('expiresIn', $response->json());
    }

    public function testGettingAccessTokenFromCache()
    {
        $token = (string) AccessToken::get('test');
        $this->assertTrue(strlen($token) > 10);
    }
}
