<?php

namespace App\core;

abstract class AbstractForm
{
    /**
     * @var string
     */
    protected $error;
    
    /**
     * @var array
     */
    protected $requestParams = [];


    /**
     * @return bool
     */
    public abstract function handle();
    
    /**
     * @return string
     */
    public final function getError()
    {
        return (string) $this->error;
    }
    
    /**
     * @param array $params
     * @return this
     */
    public final function setRequestParams(array $params = [])
    {
        $this->requestParams = $params;
        
        return $this;
    }
    
}
