<?php

namespace App\Service;

use App\Entity\Lecture;
use App\Repository\LectureRepository;

class LectureService
{
    /**
     * @var LectureRepository
     */
    private $repository;
    
    public function __construct()
    {
        $this->repository = new LectureRepository();
    }
    
    /**
     * @param Lecture $item
     * @return void
     */
    public function save(Lecture $item)
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
     * @return Lecture | false
     */
    public function getById(int $id)
    {
        $res = $this->repository->find($id);
        
        return $res ? new Lecture($res) : false;
    }
    
    /**
     * @param int $course
     * @return array
     */
    public function getShortListByCourse(int $course)
    {
        return $this->repository->getShortListByCourse($course);
    }
    
    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->repository->delete($id);
    }
    
}
