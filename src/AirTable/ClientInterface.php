<?php

namespace AirTable;


interface ClientInterface
{
    public function list();

    public function retrieve(string $id);

    public function create(array $data);

    public function delete(string $id);

    public function update(string $id, array $data);
}