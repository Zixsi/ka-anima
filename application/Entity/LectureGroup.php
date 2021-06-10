<?php

namespace App\Entity;

use App\core\Entity;
use DateTime;

/**
 * @property string $groupId
 * @property string $lectureId
 * @property DateTime $ts
 */
class LectureGroup extends Entity
{
    /**
     * @var array
     */
    protected $attributes = [
        'groupId' => 0,
        'lectureId' => 0,
        'ts' => null
    ];
    
    /**
     * @var array
     */
    protected $datamap = [
        'group_id' => 'groupId',
        'lecture_id' => 'lectureId'
    ];
    
    /**
     * @var array 
     */
    protected $dates = ['ts'];
    
    /**
     * @var array
     */
    protected $casts = [
        'groupId' => 'int',
        'lectureId' => 'int'
    ];
    
    /**
     * @return array
     */
    public function toDbArray()
    {
        $params = $this->toArray();        
        $params['ts'] = $this->ts->format('Y-m-d H:i:s');
        
        return $params;
    }
}
