<?php

namespace Esyede\BiSnap;

use Exception;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Esyede\BiSnap\Config;

class RequestLogger
{
    protected $response;

    /**
     * Constructor
     *
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function dispatch(Response $response)
    {
        return new static($response);
    }

    /**
     * Get logger instance
     *
     * @return Logger|null
     */
    public function getLogger()
    {
        if (is_null(Config::instance()->logChannel())) {
            return null;
        }

        return Log::channel(Config::instance()->logChannel());
    }

    /**
     * Get log message
     *
     * @param Request $request
     * @return string
     */
    public function message(Request $request)
    {
        return implode(' ', [
            (string) $request->getMethod(),
            (string) $request->getUri(),
            $this->response->status(),
        ]);
    }

    /**
     * Get request context
     *
     * @param Request $request
     *
     * @return array
     */
    public function requestContext(Request $request)
    {
        $body = json_decode($request->getBody(), true);
        $headers = $request->getHeaders();

        return [
            'body' => $this->censorBody($body),
            'headers' => $this->censorHeaders($headers),
        ];
    }

    /**
     * Get response context
     *
     * @return array
     */
    public function responseContext()
    {
        return [
            'body' => $this->censorBody($this->response->json()),
            'headers' => $this->censorHeaders($this->response->headers()),
        ];
    }

    /**
     * Censor confidential header items
     *
     * @param array $headers
     *
     * @return array
     */
    private function censorHeaders(array $headers)
    {
        if (Config::instance()->isDebug()) {
            return $headers;
        }

        $censoredKeys = [
            'authorization',
            'x-signature',
        ];

        return collect($headers)->map(function ($value, $key) use ($censoredKeys) {
            return (in_array(strtolower($key), $censoredKeys) && is_array($value) && !empty($value))
                ? ['**********']
                : $value;
        })->toArray();
    }

    /**
     * Censor confidential body items
     *
     * @param array $body
     *
     * @return array
     */
    private function censorBody(array $body)
    {
        if (Config::instance()->isDebug()) {
            return $body;
        }

        $censoredKeys = [
            'accessToken',
        ];

        return collect($body)->map(function ($value, $key) use ($censoredKeys) {
            return in_array($key, $censoredKeys) ? '**********' : $value;
        })->toArray();
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if (!$this->getLogger()) {
            return;
        }

        try {
            $request = $this->response->transferStats->getRequest();

            $this->getLogger()->log(
                Config::instance()->isDebug() ? 'DEBUG' : 'INFO',
                $this->message($request),
                ['request' => $this->requestContext($request), 'response' => $this->responseContext()]
            );
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
