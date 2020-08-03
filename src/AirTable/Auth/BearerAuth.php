<?php

namespace AirTable\Auth;


class BearerAuth implements AuthInterface
{
    private $token;

    public function __construct(string $token)
    {
        $this->setToken($token);
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    private function setToken($token): void
    {
        $this->token = $token;
    }



    public function getHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->getToken()}",
            'content-type' => 'application/json',
        ];
    }
}