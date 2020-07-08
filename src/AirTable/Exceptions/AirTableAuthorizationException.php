<?php

namespace AirTable\Exceptions;

class AirTableAuthorizationException extends AirTableApiException
{
    /**
     *
     */
    const HTTP_CODE = 401;

    /**
     * AirTableAuthorizationException constructor.
     * @param array $responseHeaders
     * @param null $responseBody
     */
    public function __construct($responseHeaders = array(), $responseBody = null)
    {
        $this->parseResponseBody($responseBody);

        parent::__construct(trim($this->message), self::HTTP_CODE, $responseHeaders, $responseBody);
    }
}