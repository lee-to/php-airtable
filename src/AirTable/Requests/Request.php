<?php

namespace AirTable\Requests;


use AirTable\HttpClients\AirTableHttpClientFactory;
use AirTable\HttpClients\Interfaces\AirTableHttpClientInterface;
use AirTable\Requests\Interfaces\RequestInterface;

/**
 * Class Request
 * @package AirTable
 */
class Request implements RequestInterface
{

    /**
     * @var AirTableHttpClientInterface
     */
    protected $client;

    /**
     * @var
     */
    protected $rateLimit;
    /**
     * @var array
     */
    protected $headers = [];

    /**
     * Request constructor.
     * @param string|null $handler
     * @param int|null $rateLimit
     * @throws \AirTable\Exceptions\AirTableException
     */
    public function __construct(string $handler = null, int $rateLimit = null)
    {
        $this->setRateLimit($rateLimit);

        $this->setClient(AirTableHttpClientFactory::createHttpClient($handler));
    }

    /**
     * @return RateLimit
     */
    public function getRateLimit() : RateLimit {
        return $this->rateLimit;
    }

    /**
     * @param int $rateLimit
     */
    public function setRateLimit(int $rateLimit) : void {
        $this->rateLimit = new RateLimit($rateLimit);
    }

    /**
     * @return AirTableHttpClientInterface
     */
    public function getClient(): AirTableHttpClientInterface
    {
        return $this->client;
    }

    /**
     * @param AirTableHttpClientInterface $client
     */
    public function setClient(AirTableHttpClientInterface $client): void
    {
        $this->client = $client;
    }


    /**
     * @return array
     */
    public function getHeaders() : array {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers) : void {
        $this->headers = $headers;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $body
     * @return array
     */
    public function sendRequest(string $url, string $method, array $body = []) : array {

        $this->getRateLimit()->start();

        return $this->decodeData($this->getClient()->send($url, $method, $body, $this->getHeaders()));
    }

    /**
     * @param array $data
     * @return string
     */
    public function buildQuery(array $data) : string {
        return http_build_query($data);
    }

    /**
     * @param string $data
     * @return array
     */
    protected function decodeData(string $data) : array
    {
        $result = json_decode($data, true);


        return $result;
    }
}