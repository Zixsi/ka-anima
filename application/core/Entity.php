<?php

namespace App\core;

use DateTime;
use Exception;
use JsonSerializable;
use ReflectionException;
use stdClass;

class Entity implements JsonSerializable
{
    /**
     * @var array 
     */
    protected $availableFields = [];
    
    /**
     * Карта преобразований имен свойств
     *
     * Example:
     *  $datamap = [
     *      'db_field' => 'classField'
     *  ];
     * 
     * @var array
     */
    protected $datamap = [];
    
    /**
     * @var array 
     */
    protected $dates = 
        [
            'created_at',
            'updated_at',
            'deleted_at',
        ];

    /**
     * Карта преобразований свойств
     * 
     * Example:
     *  $datamap = [
     *      'db_field' => 'int'
     *  ];
     * 
     * @var array
     * */
    protected $casts = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $original = [];

    /**
     * @var boolean
     * */
    private $cast = true;

    /**
     * @param array $data
     * @return void
     */
    public function __construct($data = [])
    {
        $this->syncOriginal();
        $this->availableFields = array_keys($this->attributes);
        $this->fill($data);
    }

    /**
     * @param array $data
     * @return \Core\Entity
     */
    public function fill(array $data = null)
    {
        if (is_array($data) === false) {
            return $this;
        }

        foreach ($data as $key => $value) {
            $key = $this->mapProperty($key);
            $method = 'set' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));

            if (method_exists($this, $method)) {
                $this->$method($value);
            } else {
                $this->attributes[$key] = $value;
            }
        }

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * @param bool $onlyChanged
     * @param bool $cast
     * @return array
     * @throws \Exception
     */
    public function toArray(bool $onlyChanged = false, bool $cast = true, bool $onlyAvailable = true)
    {
        $this->cast = $cast;
        $result = [];

        foreach ($this->attributes as $key => $value) {
            if (strpos($key, '_') === 0) {
                continue;
            }

            if ($onlyChanged && $this->hasChanged($key) === false) {
                continue;
            }
            
            if ($onlyAvailable && in_array($key, $this->availableFields) === false) {
                continue;
            }

            $result[$key] = $this->__get($key);
        }

        if (is_array($this->datamap)) {
            foreach ($this->datamap as $from => $to) {
                if (array_key_exists($to, $result)) {
                    $result[$from] = $this->__get($to);
                    unset($result[$to]);
                }
            }
        }

        $this->cast = true;
        
        return $result;
    }

    //--------------------------------------------------------------------

    /**
     * @return array
     */
    public function toRawArray(bool $onlyChanged = false)
    {
        $result = [];

        if ($onlyChanged === false) {
            return $this->attributes;
        }

        foreach ($this->attributes as $key => $value) {
            if ($this->hasChanged($key) === false) {
                continue;
            }

            $result[$key] = $this->attributes[$key];
        }

        return $result;
    }

    //--------------------------------------------------------------------

    /**
     * @return \Core\Entity
     */
    public function syncOriginal()
    {
        $this->original = $this->attributes;
        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasChanged(string $key = null)
    {
        if ($key === null) {
            return $this->original !== $this->attributes;
        }

        if (array_key_exists($key, $this->original) === false) {
            return array_key_exists($key, $this->attributes);
        }

        return $this->original[$key] !== $this->attributes[$key];
    }

    /**
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $key)
    {
        $key = $this->mapProperty($key);
        $result = null;

        $method = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));

        if (method_exists($this, $method)) {
            $result = $this->$method();
        } else if (array_key_exists($key, $this->attributes)) {
            $result = $this->attributes[$key];
        }

        if (in_array($key, $this->dates)) {
            $result = $this->mutateDate($result);
        } else if ($this->cast && empty($this->casts[$key]) === false) {
            $result = $this->castAs($result, $this->casts[$key]);
        }

        return $result;
    }

    //--------------------------------------------------------------------

    /**
     * @param string $key
     * @param null   $value
     * @return \Core\Entity
     * @throws \Exception
     */
    public function __set(string $key, $value = null)
    {
        $key = $this->mapProperty($key);

        if (in_array($key, $this->dates)) {
            $value = $this->mutateDate($value);
        }

        $isNullable = false;
        $castTo = false;

        if (array_key_exists($key, $this->casts)) {
            $isNullable = strpos($this->casts[$key], '?') === 0;
            $castTo = $isNullable ? substr($this->casts[$key], 1) : $this->casts[$key];
        }

        if ($isNullable === false || is_null($value) === false) {
            if ($castTo === 'array') {
                $value = serialize($value);
            }

            if (($castTo === 'json' || $castTo === 'json-array') && function_exists('json_encode')) {
                $value = json_encode($value);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('Json error: ' . json_last_error());
                }
            }
        }

        $method = 'set' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));
        if (method_exists($this, $method)) {
            $this->$method($value);
            return $this;
        }

        $this->attributes[$key] = $value;
        
        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * @param string $key
     * @throws ReflectionException
     */
    public function __unset(string $key)
    {
        unset($this->attributes[$key]);
    }

    //--------------------------------------------------------------------

    /**
     * @param string $key
     * @return boolean
     */
    public function __isset(string $key): bool
    {
        $key = $this->mapProperty($key);
        $method = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));
        if (method_exists($this, $method)) {
            return true;
        }

        return isset($this->attributes[$key]);
    }

    /**
     * @param  array $data
     * @return $this
     */
    public function setAttributes(array $data)
    {
        $this->attributes = $data;
        $this->syncOriginal();
        
        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * @param string $key
     * @return mixed|string
     */
    protected function mapProperty(string $key)
    {
        if (empty($this->datamap)) {
            return $key;
        }

        if (empty($this->datamap[$key]) === false) {
            return $this->datamap[$key];
        }

        return $key;
    }

    //--------------------------------------------------------------------

    /**
     * @param mixed $value
     * @return DateTime
     * @throws \Exception
     */
    protected function mutateDate($value)
    {
        if ($value instanceof DateTime) {
            return $value;
        }

        if (is_numeric($value)) {
            $date = new DateTime();
            $date->setTimestamp($value);
            return $date;
        }

        return new DateTime($value);
    }

    //--------------------------------------------------------------------

    /**
     * @param mixed $value
     * @param string $type
     * @return mixed
     * @throws \Exception
     */
    protected function castAs($value, string $type)
    {
        if (strpos($type, '?') === 0) {
            if ($value === null) {
                return null;
            }
            $type = substr($type, 1);
        }

        switch ($type) {
            case 'int':
            case 'integer':
                $value = (int) $value;
                break;
            case 'float':
                $value = (float) $value;
                break;
            case 'double':
                $value = (double) $value;
                break;
            case 'string':
                $value = (string) $value;
                break;
            case 'bool':
            case 'boolean':
                $value = (bool) $value;
                break;
            case 'object':
                $value = (object) $value;
                break;
            case 'array':
                if (is_string($value) && (strpos($value, 'a:') === 0 || strpos($value, 's:') === 0)) {
                    $value = unserialize($value);
                }

                $value = (array) $value;
                break;
            case 'json':
                $value = $this->castAsJson($value);
                break;
            case 'json-array':
                $value = $this->castAsJson($value, true);
                break;
            case 'datetime':
                return $this->mutateDate($value);
            case 'timestamp':
                return strtotime($value);
        }

        return $value;
    }

    //--------------------------------------------------------------------

    /**
     * @param mixed   $value
     * @param boolean $asArray
     * @return mixed
     * @throws \Exception
     */
    private function castAsJson($value, bool $asArray = false)
    {
        $tmp = !is_null($value) ? ($asArray ? [] : new stdClass) : null;
        if (function_exists('json_decode') === false) {
            return $tmp;
        }

        if ((is_string($value) && strlen($value) > 1 && in_array($value[0], ['[', '{', '"'])) || is_numeric($value)) {
            $tmp = json_decode($value, $asArray);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON error: ' . json_last_error(), 1);
            }
        }

        return $tmp;
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

}
