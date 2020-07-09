<?php

namespace AirTable\Models;

use AirTable\ClientInterface;

abstract class Model implements \ArrayAccess
{
    /**
     * @var array
     */
    private $unknownProperties = [];

    /**
     * @var
     */
    protected $client;

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        $method = 'get' . ucfirst($offset);
        if (method_exists($this, $method)) {
            return true;
        }
        $method = 'get' . self::matchPropertyName($offset);
        if (method_exists($this, $method)) {
            return true;
        }
        return array_key_exists($offset, $this->unknownProperties);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        $method = 'get' . ucfirst($offset);
        if (method_exists($this, $method)) {
            return $this->{$method} ();
        }
        $method = 'get' . self::matchPropertyName($offset);
        if (method_exists($this, $method)) {
            return $this->{$method} ();
        }
        return array_key_exists($offset, $this->unknownProperties) ? $this->unknownProperties[$offset] : null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $method = 'set' . ucfirst($offset);
        if (method_exists($this, $method)) {
            $this->{$method}($value);
        } else {
            $method = 'set' . self::matchPropertyName($offset);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            } else {
                $this->unknownProperties[$offset] = $value;
            }
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $method = 'set' . ucfirst($offset);
        if (method_exists($this, $method)) {
            $this->{$method} (null);
        } else {
            $method = 'set' . self::matchPropertyName($offset);
            if (method_exists($this, $method)) {
                $this->{$method} (null);
            } else {
                unset($this->unknownProperties[$offset]);
            }
        }
    }

    /**
     * @param $propertyName
     * @return mixed|null
     */
    public function __get($propertyName)
    {
        return $this->offsetGet($propertyName);
    }

    /**
     * @param $propertyName
     * @param $value
     */
    public function __set($propertyName, $value)
    {
        $this->offsetSet($propertyName, $value);
    }

    /**
     * @param $propertyName
     * @return bool
     */
    public function __isset($propertyName)
    {
        return $this->offsetExists($propertyName);
    }

    /**
     * @param $propertyName
     */
    public function __unset($propertyName)
    {
        $this->offsetUnset($propertyName);
    }

    /**
     * @param array $sourceArray
     */
    public function fromArray(array $sourceArray)
    {
        foreach ($sourceArray as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * @param $property
     * @return null|string|string[]
     */
    private static function matchPropertyName($property)
    {
        return preg_replace('/\_(\w)/', '\1', $property);
    }
}