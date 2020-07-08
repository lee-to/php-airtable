<?php

namespace AirTable\Responses;


use AirTable\Exceptions\AirTableAuthorizationException;
use AirTable\Exceptions\AirTableBadApiRequestEntityException;
use AirTable\Exceptions\AirTableNotFoundException;
use AirTable\Exceptions\AirTableRequestException;

/**
 * Class RawResponse
 * @package AirTable\Responses
 */
class RawResponse
{
    /**
     * @var
     */
    protected $code;
    /**
     * @var
     */
    protected $headers;
    /**
     * @var
     */
    protected $body;

    /**
     * RawResponse constructor.
     * @param $headers
     * @param $body
     * @param $code
     */
    public function __construct($headers, $body, $code)
    {
        $this->headers = $headers;

        $this->body = $body;

        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @throws AirTableRequestException
     * @throws AirTableNotFoundException
     * @throws AirTableAuthorizationException
     * @throws AirTableBadApiRequestEntityException
     */

    public function handleError()
    {
        switch ($this->getCode()) {
            case AirTableBadApiRequestEntityException::HTTP_CODE:
                throw new AirTableBadApiRequestEntityException($this->getHeaders(), $this->getBody());
                break;
            case AirTableNotFoundException::HTTP_CODE:
                throw new AirTableNotFoundException($this->getHeaders(), $this->getBody());
                break;
            case AirTableAuthorizationException::HTTP_CODE:
                throw new AirTableAuthorizationException($this->getHeaders(), $this->getBody());
                break;
            default:
                throw new AirTableRequestException($this->getBody(), $this->getCode());
        }
    }
}