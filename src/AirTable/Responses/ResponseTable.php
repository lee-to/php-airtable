<?php

namespace AirTable\Responses;


use AirTable\ClientInterface;
use AirTable\Responses\Interfaces\ResponseInterface;
use AirTable\Models\TableRecords;

/**
 * Class ResponseTable
 * @package AirTable\Responses
 */
class ResponseTable implements ResponseInterface
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var
     */
    private $data;

    /**
     * ResponseTable constructor.
     * @param $client
     * @param $data
     */
    public function __construct(ClientInterface $client, $data)
    {
        $this->setClient($client);
        $this->setData($data);
    }

    /**
     * @return ClientInterface
     */
    public function getClient() : ClientInterface
    {
        return $this->client;
    }

    /**
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }


    /**
     * @return TableRecords
     */
    public function getResponse() {
        return new TableRecords($this->getClient(), $this->getData());
    }
}