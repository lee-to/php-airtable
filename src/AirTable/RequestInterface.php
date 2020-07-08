<?php

namespace AirTable;


interface RequestInterface
{
    public function getHeaders() : array;

    public function setHeaders(array $headers) : void;

    public function sendRequest(string $url, string $method, array $body = []) : array;

    public function buildQuery(array $data) : string;
}