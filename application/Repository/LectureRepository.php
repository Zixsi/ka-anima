<?php

namespace App\Repository;

use App\core\AbstractRepository;

class LectureRepository extends AbstractRepository
{
    protected $table = 'lectures';
    
    /**
     * @param int $course
     * @return array
     */
    public function getShortListByCourse(int $course)
    {
        return $this->getConnection()->fetchAllAssociative(
            sprintf(
                "SELECT 
                    id,
                    active,
                    name,
                    type,
                    sort
                FROM 
                    %s 
                WHERE 
                    course_id = :course 
                ORDER BY 
                    type DESC, 
                    sort ASC, 
                    id ASC",
                $this->table
            ),
            [':course' => $course]
        );
    }
    
}
