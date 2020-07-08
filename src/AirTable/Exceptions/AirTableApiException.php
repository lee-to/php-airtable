<?php

namespace AirTable\Exceptions;

use Exception;

class AirTableApiException extends Exception
{
    /**
     * @var mixed
     */
    protected $responseBody;

    /**
     * @var string[]
     */
    protected $responseHeaders;

    /**
     * @var
     */
    public $type;

    /**
     * @var string
     */
    public $message = "";

    /**
     * Constructor
     *
     * @param string $message Error message
     * @param int $code HTTP status code
     * @param string[] $responseHeaders HTTP header
     * @param mixed $responseBody HTTP body
     */
    public function __construct($message = "", $code = 0, $responseHeaders = array(), $responseBody = null)
    {
        parent::__construct($message, $code);

        $this->responseHeaders = $responseHeaders;
        $this->responseBody = $responseBody;
    }

    /**
     * @param $responseBody
     */
    public function parseResponseBody($responseBody) {
        $errorData = json_decode($responseBody, true);

        if (isset($errorData['error']['message'])) {
            $this->message .= $errorData['error']['message'] . '. ';
        } elseif(isset($errorData['error'])) {
            $this->message .= $errorData['error'] . '. ';
        }


        if (isset($errorData['error']['type'])) {
            $this->type = $errorData['error']['type'];
        }
    }

    /**
     * @return string[]
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * @return mixed
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }
}