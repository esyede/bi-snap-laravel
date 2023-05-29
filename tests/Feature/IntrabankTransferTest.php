<?php

namespace Esyede\BiSnap\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Esyede\BiSnap\Amount;
use Esyede\BiSnap\Config;
use Esyede\BiSnap\IntrabankTransfer\Payload;
use Esyede\BiSnap\IntrabankTransfer\Transfer;
use Esyede\BiSnap\Tests\TestCase;

class IntrabankTransferTest extends TestCase
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
            'http://bi-snap-laravel.test/api-test/v1.0/transfer-intrabank' => Http::response([
                'responseCode' => '2001700',
                'responseMessage' => 'Sucessful',
                'beneficiaryAccountNo' => '0613008761',
                'amount' => [
                    'value' => '100.00',
                    'currency' => 'IDR',
                ],
                'referenceNo' => microtime(),
                'additionalInfo' => [
                    'economicActivity' => '',
                    'transactionPurpose' => '',
                ],
                'partnerReferenceNo' => 'TEST1682707741',
                'sourceAccountNo' => '0611116411',
                'transactionDate' => '2023-04-28T18:51:00+07:00',
            ]),
        ]);
    }

    public function testIntrabankTransfer()
    {
        Config::load('test')->debug(true);

        $transfer = new Transfer(
            'http://bi-snap-laravel.test',
            '95051',
            time()
        );

        $response = $transfer->send(new Payload(
            'TEST1682707741',
            new Amount(100),
            '0613008761',
            '0611116411',
            '2023-04-28T18:51:00+07:00',
            'test@bi-snap-laravel.test',
            'IDR',
            'INV00001',
            null,
            'Testing doang',
            ['by' => 'Suyadi', 'ganteng' => true],
        ));

        $this->assertTrue($response->status() === 200);
    }
}
