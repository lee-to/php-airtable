<?php

namespace AirTable\Models;


use AirTable\ClientInterface;
use AirTable\Exceptions\AirTableException;
use AirTable\Models\Interfaces\RecordInterface;

/**
 * Class Record
 * @package AirTable\Models
 */
class Record extends Model implements RecordInterface
{
    /**
     * @var
     */
    protected $id;

    /**
     * @var
     */
    protected $fields;

    /**
     * @var
     */
    protected $createdTime;

    /**
     * @var bool
     */
    protected $deleted = false;

    /**
     * Record constructor.
     * @param ClientInterface $client
     * @param null $data
     */
    public function __construct(ClientInterface $client, $data = null)
    {
        $this->setClient($client) ;

        if (!empty($data) && is_array($data)) {
            $this->fromArray($data);
        }
    }

    /**
     * @param $createdTime
     */
    public function setCreatedTime($createdTime) {
        $this->createdTime = $createdTime;
    }

    /**
     * @return string
     */
    public function getId() : string {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param $fields
     */
    public function setFields($fields) {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function getFields() : array {
        return $this->fields;
    }

    /**
     * @return string
     */
    public function getCreatedTime() : string {
        return $this->createdTime;
    }

    /**
     * @param $deleted
     */
    public function setDeleted($deleted) {
        $this->deleted = $deleted;
    }

    /**
     * @return bool
     */
    public function getDeleted() : bool {
        return $this->deleted;
    }

    /**
     * @return bool
     */
    public function isDeleted() : bool {
        return $this->deleted;
    }

    /**
     * @param array $data
     * @return RecordInterface
     */
    public function update(array $data = []) : RecordInterface {
        return $this->getClient()->update($this->getId(), $data);
    }

    /**
     * @return RecordInterface
     */
    public function delete() : RecordInterface {
        return $this->getClient()->delete($this->getId());
    }

    /**
     * @param string $name
     * @return mixed
     * @throws AirTableException
     */
    public function __get($name)
    {
        $fields = $this->getFields();

        if(isset($fields[$name])) {
            return $fields[$name];
        } else {
            throw new AirTableException('Field not exist: ' . $name);
        }
    }

    /**
     * @param array $sourceArray
     */
    public function fromArray(array $sourceArray)
    {
        parent::fromArray($sourceArray);
    }
}