<?php

namespace AirTable;

use Exception;

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
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var
     */
    protected $httpClient = "";


    /**
     * AirTable constructor.
     * @param array $config [configuration]
     *     @option string  token [api token]
     *     @option string  base [api base]
     *     @option string  http_client [http client]
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        $this->configuration($config);
    }


    /**
     * @param array $config [configuration]
     *     @option string  token [api token]
     *     @option string  base [api base]
     *     @option string  http_client [http client]
     * @throws Exception
     */
    private function configuration(array $config) : void {
        if(!isset($config["token"])) {
            throw new Exception("Token is required");
        }

        if(!isset($config["base"])) {
            throw new Exception("Base id is required");
        }

        $this->setToken($config["token"]);
        $this->setBase($config["base"]);

        if(isset($config["http_client"])) {
            $this->setHttpClient($config["http_client"]);
        }
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
    protected function getClient() : ClientInterface {
        return $this->client;
    }

    /**
     * return void
     */
    private function initClient() : void {
        $this->client = new Client($this->getToken(), $this->getBase(), $this->getTable(), $this->getHttpClient());
    }

    /**
     * @param string $table
     * @return Client
     */
    public function table(string $table) : ClientInterface {
        $this->setTable($table);

        $this->initClient();

        return $this->getClient();
    }
}