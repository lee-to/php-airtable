<?php

namespace AirTable\HttpClients;


use AirTable\Exceptions\AirTableRequestException;
use AirTable\HttpClients\Interfaces\AirTableHttpClientInterface;
use AirTable\Responses\RawResponse;


/**
 * Class AirTableCurlHttpClient
 * @package AirTable\HttpClients
 */
class AirTableCurlHttpClient implements AirTableHttpClientInterface
{
    /**
     * @var
     */
    protected $client;

    /**
     * AirTableCurlHttpClient constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return $client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $client
     */
    public function setClient($client): void
    {
        $this->client = $client;
    }


    /**
     * @param array $headers
     * @return array
     */
    public function compileRequestHeaders(array $headers)
    {
        $return = [];

        foreach ($headers as $key => $value) {
            $return[] = $key . ': ' . $value;
        }

        return $return;
    }


    /**
     * @param $url
     * @param $method
     * @param $body
     * @param array $headers
     * @return mixed
     * @throws AirTableRequestException
     * @throws AirTable\Exceptions\AirTableAuthorizationException
     * @throws AirTable\Exceptions\AirTableBadApiRequestEntityException
     * @throws AirTable\Exceptions\AirTableNotFoundException
     */
    public function send($url, $method, $body, array $headers)
    {
        $this->setClient(curl_init());

        curl_setopt($this->client, CURLOPT_URL, $url);

        curl_setopt($this->client, CURLOPT_HTTPHEADER, $this->compileRequestHeaders($headers));

        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($this->client, CURLOPT_CUSTOMREQUEST, $method);

        curl_setopt($this->client, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($this->client, CURLOPT_SSL_VERIFYPEER, FALSE);

        if ($method !== "GET" && isset($body["json"])) {
            curl_setopt($this->client,CURLOPT_POSTFIELDS, json_encode($body["json"]));
        } elseif(isset($body["query"])) {
            curl_setopt($this->client, CURLOPT_URL, $url . "?" . $body["query"]);
        }

        $data = curl_exec($this->client);

        $response = new RawResponse([], $data, curl_getinfo($this->client, CURLINFO_HTTP_CODE));

        if($response === false) {
            curl_error($this->client);
        }

        if (curl_errno($this->client) || curl_getinfo($this->client, CURLINFO_HTTP_CODE) != 200) {
            $response->handleError();
        }

        curl_close($this->client);

        return $response->getBody();
    }
}