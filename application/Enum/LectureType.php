<?php

namespace App\Enum;

use App\core\AbstractEnum;

class LectureType extends AbstractEnum
{
    public const COMMON = 0;
    public const INTRO = 1;
    
    /**
     * @var array
     */
    protected static $validValues = [
        self::COMMON => true,
        self::INTRO => true
    ];
    
    /**
     * @return array
     */
    public static function getViewList()
    {
        return [
            self::COMMON => ['title' => 'Обычная', 'value' => self::COMMON],
            self::INTRO => ['title' => 'Вводная', 'value' => self::INTRO]
        ];
    }
}
