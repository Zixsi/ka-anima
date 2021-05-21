<?php

namespace App\Validator;

class UserSettingsValidator extends AbstractValidator
{
    /**
     * @var array 
     */
    protected $rules = [
        'last_name' => ['optional', ['lengthMin', 1], ['lengthMax', 255]],
        'first_name' => ['optional', ['lengthMin', 1], ['lengthMax', 255]],
        'nick' => ['optional', ['lengthMin', 1], ['lengthMax', 255]],
        'phone' => ['optional', ['lengthMin', 1], ['lengthMax', 40], ['regex', '/^([0-9\+\(\),])+$/i']],
    ];
    
}
