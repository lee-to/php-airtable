<?php

namespace AirTable\Models\Interfaces;


interface RecordInterface
{
    public function getId() : string;

    public function getFields() : array;

    public function update(array $data = []) : RecordInterface;

    public function delete() : RecordInterface;

    public function isDeleted() : bool;
}