<?php

namespace AirTable\Exceptions;

class AirTableNotFoundException extends AirTableApiException
{
    /**
     *
     */
    const HTTP_CODE = 404;

    /**
     * AirTableNotFoundException constructor.
     * @param array $responseHeaders
     * @param null $responseBody
     */
    public function __construct($responseHeaders = array(), $responseBody = null)
    {
        $this->parseResponseBody($responseBody);

        parent::__construct(trim($this->message), self::HTTP_CODE, $responseHeaders, $responseBody);
    }
}