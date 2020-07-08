<?php

namespace AirTable\Models\Interfaces;


interface TableInterface
{
    /**
     * @return RecordInterface[]
     */
    public function getRecords() : array;

    public function getOffset();

    public function count();
}