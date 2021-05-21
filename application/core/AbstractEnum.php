<?php

namespace App\core;

abstract class AbstractEnum
{
    /**
     * @var array
     */
    protected static $validValues = [];

    /**
     * @param mixed $value
     * @return bool
     */
    public static function valueExists($value)
    {
        return array_key_exists($value, static::$validValues);
    }

    /**
     * @return array
     */
    public static function getValidValues()
    {
        return array_keys(static::$validValues);
    }

    /**
     * @return string[]
     */
    public static function getEnabledValues()
    {
        $result = [];
        
        foreach (static::$validValues as $key => $enabled) {
            if ($enabled) {
                $result[] = $key;
            }
        }
        
        return $result;
    }
}
