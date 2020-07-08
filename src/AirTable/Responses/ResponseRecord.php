<?php

namespace AirTable\Responses;


use AirTable\ClientInterface;
use AirTable\Responses\Interfaces\ResponseInterface;
use AirTable\Models\Record;

/**
 * Class ResponseRecord
 * @package AirTable\Responses
 */
class ResponseRecord implements ResponseInterface
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
     * ResponseRecord constructor.
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
    public function getClient()
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
     * @return Record
     */
    public function getResponse() {
        return new Record($this->getClient(), $this->getData());
    }
}