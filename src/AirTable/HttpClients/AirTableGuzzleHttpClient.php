<?php

namespace AirTable\HttpClients;


use AirTable\Exceptions\AirTableAuthorizationException;
use AirTable\Exceptions\AirTableBadApiRequestEntityException;
use AirTable\Exceptions\AirTableNotFoundException;
use AirTable\Exceptions\AirTableRequestException;
use AirTable\HttpClients\Interfaces\AirTableHttpClientInterface;
use AirTable\Responses\RawResponse;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class AirTableGuzzleHttpClient
 * @package AirTable\HttpClients
 */
class AirTableGuzzleHttpClient implements AirTableHttpClientInterface
{
    /**
     * @var Client
     */
    protected $client;


    /**
     * AirTableGuzzleHttpClient constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->setClient($client ?: new Client());
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }


    /**
     * @param $url
     * @param $method
     * @param $body
     * @param array $headers
     * @return \Psr\Http\Message\StreamInterface
     * @throws AirTableBadApiRequestEntityException
     * @throws AirTableRequestException
     * @throws AirTableAuthorizationException
     * @throws AirTableNotFoundException
     * @throws RequestException
     * @throws GuzzleException
     */
    public function send($url, $method, $body, array $headers)
    {
        $options = [
            'base_uri' => $url,
            'verify' => false,
            'headers' => $headers,
        ];

        if(!empty($body)) {
            $options = array_merge($options, $body);
        }

        try {
            $request = $this->getClient()->request($method, $url, $options);

        } catch (RequestException $e) {
            $response = new RawResponse($e->getResponse()->getHeaders(), $e->getResponse()->getBody(), $e->getCode());

            $response->handleError();
        }

        return $request->getBody();
    }


}