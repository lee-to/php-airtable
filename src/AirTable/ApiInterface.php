<?php

namespace AirTable;


interface ApiInterface
{
    public function getEndpointUrl(string $id = "") : string;

    public function getParams() : array;
}