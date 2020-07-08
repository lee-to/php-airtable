<?php

namespace AirTable;


use AirTable\Models\Interfaces\RecordInterface;
use AirTable\Models\Interfaces\TableInterface;
use AirTable\Responses\ResponseRecord;
use AirTable\Responses\ResponseTable;

/**
 * AirTable\Client
 *
 * @method Client fields(array $fields)
 * @method Client sort(string $field, string $direction)
 * @method Client filterByFormula(string $column, string $operator, string $value)
 * @method Client pageSize(int $pageSize)
 * @method Client offset(string $offset)
 * @method Client maxRecords(int $maxRecords)
 *
 * @mixin Client
 */

class Client implements ClientInterface
{
    /**
     * @var ApiInterface
     */
    protected $api;

    /**
     * @var RequestInterface
     */
    protected $requests;

    /**
     * Client constructor.
     * @param string $token
     * @param string $base
     * @param string $table
     * @param string|null $handler
     */
    public function __construct(string $token, string $base, string $table, string $handler = null)
    {
        $this->setApi($token, $base, $table);
        $this->setRequests($handler);
    }

    /**
     * @param string $handler
     */
    protected function setRequests(string $handler) : void
    {
        $this->requests = new Request($handler, $this->getApi()->getRateLimit());
        $this->requests->setHeaders($this->getApi()->getHeaders());
    }

    /**
     * @return Request
     */
    protected function getRequests() : RequestInterface
    {
        return $this->requests;
    }

    /**
     * @param string $token
     * @param string $base
     * @param string $table
     */
    protected function setApi(string $token, string $base, string $table) : void
    {
        $this->api = new Api($token, $base, $table);
    }

    /**
     * @return Api
     */
    protected function getApi() : ApiInterface
    {
        return $this->api;
    }

    /**
     * @param string $id
     * @return RecordInterface
     */
    public function retrieve(string $id) : RecordInterface
    {
        $response = new ResponseRecord($this, $this->getRequests()->sendRequest($this->getApi()->getEndpointUrl($id), "GET"));

        return $response->getResponse();
    }

    /**
     * @return TableInterface
     */
    public function list() : TableInterface
    {
        $body = $this->getRequests()->buildQuery($this->getApi()->getParams());

        $response = new ResponseTable($this, $this->getRequests()->sendRequest($this->getApi()->getEndpointUrl(), "GET", ["query" => $body]));

        return $response->getResponse();
    }

    /**
     * @param array $data
     * @return RecordInterface
     */
    public function create(array $data = []) : RecordInterface
    {
        $response = new ResponseRecord($this, $this->getRequests()->sendRequest($this->getApi()->getEndpointUrl(), "POST", ["json" => ["fields" => $data]]));

        return $response->getResponse();
    }

    /**
     * @param string $id
     * @return RecordInterface
     */
    public function delete(string $id) : RecordInterface
    {
        $response = new ResponseRecord($this, $this->getRequests()->sendRequest($this->getApi()->getEndpointUrl($id), "DELETE"));

        return $response->getResponse();
    }

    /**
     * @param string $id
     * @param array $data
     * @return RecordInterface
     */
    public function update(string $id, array $data = []) : RecordInterface
    {
        $response = new ResponseRecord($this, $this->getRequests()->sendRequest($this->getApi()->getEndpointUrl($id), "PATCH", ["json" => ["fields" => $data]]));

        return $response->getResponse();
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if(method_exists($this->getApi(), $name)) {
            call_user_func_array([$this->getApi(), $name], $arguments);
        }

        return $this;
    }
}