<?php

namespace Esyede\BiSnap;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Client
{
    protected $config;
    protected $pendingRequest = null;
    protected $throwError;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->throwError = data_get($options, 'throw', true);
    }

    /**
     * PHP magic method, triggered when invoking inaccessible methods in an object context
     *
     * @param string $method
     * @param array|null $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (!$this->pendingRequest) {
            $this->pendingRequest = Http::acceptJson();
        }

        if ('withHeaders' === $method) {
            $this->pendingRequest->withHeaders(...$arguments);
            return $this;
        }

        if (in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
            $response = $this->pendingRequest->$method(...$arguments);

            RequestLogger::dispatch($response);

            $this->pendingRequest = null;

            if ($this->throwError) {
                $response = $response->throw();
            }

            return $response;
        }

        $this->pendingRequest->$method(...$arguments);
        return $this;
    }
}
