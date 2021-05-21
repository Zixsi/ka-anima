<?php

namespace App\Entity;

use App\core\Entity;
use DateTime;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $text
 * @property ?string $img
 * @property DateTime $ts
 * @property bool $active
 */
class News extends Entity
{
    /**
     * @var array
     */
    protected $attributes = [
        'id' => 0,
        'title' => null,
        'description' => null,
        'text' => null,
        'img' => null,
        'ts' => null,
        'active' => true
    ];
    
    /**
     * @var array
     */
    protected $datamap = [];
    
    /**
     * @var array 
     */
    protected $dates = ['ts'];
    
    /**
     * @var array
     */
    protected $casts = [
        'id' => 'int',
        'title' => 'string',
        'description' => 'string',
        'text' => 'string',
        'img' => '?string',
        'active' => 'bool'
    ];
    
    /**
     * @return array
     */
    public function toDbArray()
    {
        $params = $this->toArray();
        unset($params['id']);
        
        $params['ts'] = $this->ts->format('Y-m-d H:i:s');
        $params['active'] = (int) $this->active;
        
        return $params;
    }
}
