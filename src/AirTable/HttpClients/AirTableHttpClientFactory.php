<?php

namespace AirTable\HttpClients;

use AirTable\Exceptions\AirTableException;
use AirTable\HttpClients\Interfaces\AirTableHttpClientInterface;

/**
 * Class AirTableHttpClientFactory
 * @package AirTable\HttpClients
 */
class AirTableHttpClientFactory
{
    /**
     * AirTableHttpClientFactory constructor.
     */
    private function __construct()
    {

    }

    /**
     * @param AirTableHttpClientInterface|string|null $handler
     *
     * @throws \AirTable\Exceptions\AirTableException
     *
     * @return AirTableHttpClientInterface
     */
    public static function createHttpClient($handler)
    {
        if (!$handler) {
            return self::getDefault();
        }

        if ($handler instanceof AirTableHttpClientInterface) {
            return $handler;
        }


        if ('curl' === $handler) {
            if (!extension_loaded('curl')) {
                throw new AirTableException('The cURL extension must be loaded in order to use the "curl" handler.');
            }

            return new AirTableCurlHttpClient();
        }

        if ('guzzle' === $handler && !class_exists('GuzzleHttp\Client')) {
            throw new AirTableException('The Guzzle HTTP client must be included in order to use the "guzzle" handler.');
        }

        if ('guzzle' === $handler) {
            return new AirTableGuzzleHttpClient();
        }

        if ('curl' === $handler) {
            return new AirTableCurlHttpClient();
        }

        throw new AirTableException('The http client handler must be set to "curl", "guzzle", be an instance of GuzzleHttp\Client or an instance of AirTable\HttpClients\Interfaces\AirTableHttpClientInterface');
    }

    /**
     * @return AirTableCurlHttpClient|AirTableGuzzleHttpClient
     */
    private static function getDefault()
    {
        if (class_exists('GuzzleHttp\Client')) {
            return new AirTableGuzzleHttpClient();
        }

        return new AirTableCurlHttpClient();
    }
}