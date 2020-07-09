<?php

namespace AirTable;


use AirTable\HttpClients\AirTableHttpClientFactory;
use AirTable\HttpClients\Interfaces\AirTableHttpClientInterface;

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
     * @var
     */
    protected $lastRequestTime;
    /**
     * @var
     */
    protected $requestCount;


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
     * @return int
     */
    public function getRateLimit() : int {
        return $this->rateLimit;
    }

    /**
     * @param int $rateLimit
     */
    public function setRateLimit(int $rateLimit) : void {
        $this->rateLimit = $rateLimit;
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
     * @return mixed
     */
    public function getLastRequestTime()
    {
        return $this->lastRequestTime;
    }

    /**
     * @param mixed $lastRequestTime
     */
    public function setLastRequestTime($lastRequestTime): void
    {
        $this->lastRequestTime = $lastRequestTime;
    }

    /**
     * @return mixed
     */
    public function getRequestCount()
    {
        return $this->requestCount;
    }

    /**
     * @param mixed $requestCount
     */
    public function setRequestCount($requestCount): void
    {
        $this->requestCount = $requestCount;
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

        $this->rateLimit();

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
     * @return void
     */
    protected function rateLimit() : void {
        if(!is_null($this->getRateLimit())) {
            $this->setLastRequestTime(time());
            $this->setRequestCount($this->getRequestCount()+1);

            while ($this->getLastRequestTime() == time() && $this->getRequestCount() > $this->getRateLimit()) {
                usleep(100000);
            }

            if ($this->getLastRequestTime() != time()) {
                $this->setRequestCount(0);
            }
        }
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