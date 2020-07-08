<?php

namespace AirTable\Models;


use AirTable\ClientInterface;
use AirTable\Models\Interfaces\RecordInterface;
use AirTable\Models\Interfaces\TableInterface;

/**
 * Class TableRecords
 * @package AirTable\Models
 */
class TableRecords extends Model implements TableInterface, \IteratorAggregate, \Countable
{
    /**
     * @var
     */
    private $records;
    /**
     * @var bool
     */
    private $offset = false;

    /**
     * TableRecords constructor.
     * @param ClientInterface $client
     * @param null $data
     */
    public function __construct(ClientInterface $client, $data = null)
    {
        $this->setClient($client);

        if (!empty($data) && is_array($data)) {
            $this->fromArray($data);
        }
    }

    /**
     * @param $records
     */
    public function setRecords($records) {
        $this->records = $records;
    }

    /**
     * @param $offset
     */
    public function setOffset($offset) {
        $this->offset = $offset;
    }

    /**
     * @return RecordInterface[]
     */
    public function getRecords() : array {
        return $this->records;
    }

    /**
     * @return bool
     */
    public function getOffset() {
        return $this->offset;
    }


    /**
     * @return \Generator|\Traversable
     */
    public function getIterator()
    {
        yield from $this->getRecords();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getRecords());
    }

    /**
     * @param array $sourceArray
     */
    public function fromArray(array $sourceArray) : void
    {
        if (!empty($sourceArray['records'])) {

            foreach ($sourceArray['records'] as $i => $itemArray) {

                if (is_array($itemArray)) {
                    $sourceArray['records'][$i] = new Record($this->getClient(), $itemArray);
                }
            }
        }

        parent::fromArray($sourceArray);
    }
}