<?php

namespace AirTable;


interface ApiInterface
{
    public function getEndpointUrl(string $id = "") : string;

    public function getHeaders() : array;

    public function getParams() : array;
}