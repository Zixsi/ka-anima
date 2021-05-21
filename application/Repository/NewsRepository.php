<?php

namespace App\Repository;

use App\core\AbstractRepository;

class NewsRepository extends AbstractRepository
{
    protected $table = 'news';
    
    /**
     * @param int $limit
     * @return array
     */
    public function getListLatestByTimestamp($limit = 20)
    {
        return $this->getConnection()->fetchAllAssociative(
            sprintf(
                "SELECT 
                    id, 
                    title,
                    description,
                    img,
                    ts
                FROM 
                    %s 
                WHERE 
                    active = 1 
                    AND ts <= NOW() 
                ORDER BY 
                    ts DESC 
                LIMIT 
                    %d",
                $this->table,
                $limit
            )
        );
    }
    
    /**
     * @return array
     */
    public function getListForAdmin()
    {
        return $this->getConnection()->fetchAllAssociative(
            sprintf(
                "SELECT 
                    id, 
                    title,
                    description,
                    img,
                    ts
                FROM 
                    %s 
                ORDER BY 
                    id DESC",
                $this->table,
                $limit
            )
        );
    }
}
