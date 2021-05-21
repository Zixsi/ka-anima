<?php

namespace App\Validator;

class NewsValidator extends AbstractValidator
{
    /**
     * @var array
     */
    protected $labels = [
        'title' => 'Заголовок',
        'description' => 'Описание',
        'text' => 'Детальное описание'
    ];

    /**
     * @var array 
     */
    protected $rules = [
        'title' => ['required', ['lengthMin', 3], ['lengthMax', 255]],
        'description' => ['required', ['lengthMin', 10], ['lengthMax', 65535]],
        'text' => ['required', ['lengthMin', 10], ['lengthMax', 65535]]
    ];
    
}
