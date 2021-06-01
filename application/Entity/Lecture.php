<?php

namespace App\Entity;

use App\core\Entity;
use DateTime;

/**
 * @property int $id
 * @property bool $active
 * @property int $courseId
 * @property string $name
 * @property string $description
 * @property string $task
 * @property int $type
 * @property string $video
 * @property DateTime $created
 * @property DateTime $modify
 * @property int $sort
 */
class Lecture extends Entity
{
    /**
     * @var array
     */
    protected $attributes = [
        'id' => 0,
        'active' => true,
        'courseId' => 0,
        'name' => null,
        'description' => null,
        'task' => null,
        'type' => 0,
        'video' => null,
        'created' => null,
        'modify' => null,
        'sort' => 500
    ];
    
    /**
     * @var array
     */
    protected $datamap = [
        'course_id' => 'courseId'
    ];
    
    /**
     * @var array 
     */
    protected $dates = [
        'created', 
        'modify'
    ];
    
    /**
     * @var array
     */
    protected $casts = [
        'id' => 'int',
        'active' => 'bool',
        'courseId' => 'int',
        'name' => 'string',
        'description' => 'string',
        'task' => 'string',
        'type' => 'int',
        'video' => 'string',
        'sort' => 'int'
    ];
    
    /**
     * @return array
     */
    public function toDbArray()
    {
        $params = $this->toArray();
        unset($params['id']);
        
        $params['created'] = $this->created->format('Y-m-d H:i:s');
        $params['modify'] = $this->modify->format('Y-m-d H:i:s');
        $params['active'] = (int) $this->active;
        
        return $params;
    }
    
    /**
     * @return array
     */
    public function toViewArray()
    {
        $params = $this->toArray();
        $params['created'] = $this->created->format('Y-m-d H:i:s');
        $params['modify'] = $this->modify->format('Y-m-d H:i:s');

        return $params;
    }
}
