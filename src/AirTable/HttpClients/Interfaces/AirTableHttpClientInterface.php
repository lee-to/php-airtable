<?php

namespace AirTable\HttpClients\Interfaces;


interface AirTableHttpClientInterface
{
    public function send($url, $method, $body, array $headers);
}