<?php

namespace App\Service;

use App\Entity\News;
use App\Repository\NewsRepository;
use App\Transformer\NewsTransformer;

class NewsService
{
    /**
     * @var NewsRepository
     */
    private $repository;
    
    public function __construct()
    {
        $this->repository = new NewsRepository();
    }
    
    /**
     * @param News $item
     * @return void
     */
    public function save(News $item)
    {
        $params = $item->toDbArray();
        
        if ($item->id > 0) {
            $this->repository->update($item->id, $params);
            return;
        }
        
        $this->repository->add($params);
        $item->id = $this->repository->getLastInsertId();
    }
    
    /**
     * @param int $id
     * @return News | false
     */
    public function getById(int $id)
    {
        $res = $this->repository->find($id);
        
        return $res ? new News($res) : false;
    }
    
    /**
     * @param int $limit
     * @return array
     */
    public function getListLatestByTimestamp($limit = 20)
    {
        return (new NewsTransformer())->runItems(
            $this->repository->getListLatestByTimestamp($limit)
        );
    }
    
    /**
     * @return array
     */
    public function getListForAdmin()
    {
        return $this->repository->getListForAdmin();
    }
    
    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->repository->delete($id);
    }
}
