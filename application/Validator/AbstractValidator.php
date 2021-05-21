<?php

namespace App\Validator;

use InvalidArgumentException;
use Valitron\Validator;

class AbstractValidator extends Validator
{ 
    /**
     * @var array
     */
    protected $labels = [];

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct([], [], 'ru');
        
        self::addRule('arrayLengthMin', function ($field, $value, array $params, array $fields) {
            $length = count($value);
            return ($length !== false) && $length >= $params[0];
        }, '{field} должно содержать не менее %d элементов');
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->_fields = $data;
        $this->_errors = [];
    }

    /**
     * @param ?string $prefix
     * @param ?string $sufix
     * @return string
     */
    public function getStringErrors($prefix = null, $sufix = null)
    {
        $result = array();
        $errors = $this->errors();
        foreach ($errors as $row) {
            $result[] = $prefix . current($row) . $sufix;
        }

        return implode("\n", $result);
    }

    /**
     * @return string
     */
    public function getError()
    {
        return 
            count($this->errors()) 
            ? current(current($this->errors())) 
            : '';
    }
    
    /**
     * @param string $lang
     * @param ?string $langDir
     * @throws InvalidArgumentException
     */
    public function setLang($lang, $langDir = null)
    {
        $curLang = $this->lang($lang);
        $curLangDir = $this->langDir($langDir);
        $langFile = rtrim($curLangDir, '/') . '/' . $curLang . '.php';
        
        if (stream_resolve_include_path($langFile)) {
            $langMessages = include $langFile;
            static::$_ruleMessages = array_merge(static::$_ruleMessages, $langMessages);
        } else {
            throw new InvalidArgumentException("Fail to load language file '" . $langFile . "'");
        }
    }
    
     /**
     * @param array $data
     * @return bool
     */
    public function run($data)
    {
        $this->setData($data);
        $this->mapFieldsRules($this->rules);
        $this->labels($this->labels);
        
        return $this->validate();
    }
}
