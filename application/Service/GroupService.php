<?php

namespace App\Service;

use App\Entity\LectureGroup;
use App\Repository\LectureGroupRepository;

class GroupService
{
    /**
     * @var LectureGroupRepository
     */
    private $repository;
    
    public function __construct()
    {
        $this->repository = new LectureGroupRepository();
    }
    
    /**
     * @param int $id
     * @return array
     */
    public function getGroupMonthMap(int $id)
    {
        return $this->repository->getGroupMonthMap($id);
    }
    
    /**
     * @param LectureGroup $item
     * @return void
     */
    public function addGroupLecture(LectureGroup $item)
    {
        $this->repository->add($item->toDbArray());
    }
    
    /**
     * @param int $id
     * @return void
     */
    public function removeLectureFromAllGroups(int $id)
    {
        $this->repository->removeByLecture($id);
    }
    
    /**
     * @param int $id
     * @return void
     */
    public function removeLecturesForGroup(int $id)
    {
        $this->repository->removeByGroup($id);
    }
    
    /**
     * @param int $id
     * @return void
     */
    public function getListPublicLecturesForGroup(int $id)
    {
        return $this->repository->getListPublicLecturesForGroup($id);
    }
}
