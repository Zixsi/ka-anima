<?php

namespace App\Validator;

class LectureValidator extends AbstractValidator
{
    /**
     * @var array
     */
    protected $labels = [
        'name' => 'Заголовок',
        'video' => 'Видео',
        'sort' => 'Сортировка'
    ];
    
    /**
     * @var array 
     */
    protected $rules = [
        'name' => ['required', ['lengthMin', 3], ['lengthMax', 255]],
        'video' => ['required', 'url'],
        'sort' => ['required', 'integer', ['min', 0], ['max', 65535]]
    ];
    
}
