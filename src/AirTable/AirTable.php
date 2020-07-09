<?php

namespace AirTable;

/**
 * Class AirTable
 * @package AirTable
 */
final class AirTable
{
    /**
     * @var
     */
    protected $token = "";

    /**
     * @var
     */
    protected $base = "";

    /**
     * @var
     */
    protected $table = "";

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var
     */
    protected $httpClient = "";


    /**
     * AirTable constructor.
     * @param string $token
     * @param string $base
     * @param string|null $handler
     */
    public function __construct(string $token, string $base, string $handler = "")
    {
        $this->setToken($token);
        $this->setBase($base);
        $this->setHttpClient($handler);
    }

    /**
     * @param string $token
     */
    protected function setToken(string $token) : void {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken() : string {
        return $this->token;
    }

    /**
     * @param string $base
     */
    protected function setBase(string $base) : void {
        $this->base = $base;
    }

    /**
     * @return string
     */
    public function getBase() : string {
        return $this->base;
    }

    /**
     * @param string $httpClient
     */
    protected function setHttpClient(string $httpClient) : void {
        $this->httpClient = $httpClient;
    }

    /**
     * @return string
     */
    public function getHttpClient() : string {
        return $this->httpClient;
    }

    /**
     * @param string $table
     */
    protected function setTable(string $table) : void {
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getTable() : string {
        return $this->table;
    }

    /**
     * @return Client
     */
    protected function getClient() : Client {
        return $this->client;
    }


    /**
     * @throws \AirTable\Exceptions\AirTableException
     */
    private function initClient() : void {
        $this->client = new Client($this->getToken(), $this->getBase(), $this->getTable(), $this->getHttpClient());
    }


    /**
     * @param string $table
     * @return Client
     * @throws /\AirTable\Exceptions\AirTableException
     */
    public function table(string $table) : Client {
        $this->setTable($table);

        $this->initClient();

        return $this->getClient();
    }
}