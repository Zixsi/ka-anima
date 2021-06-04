<?php

namespace App\Handler;

class LectureRemoveHandler
{

    /**
     * @param int $id
     * @return void
     */
    public function handle(int $id)
    {
        try {
            $db = dbConnection();
            $lectureService = new \App\Service\LectureService();
            
            $db->beginTransaction();
            $item = $lectureService->getById($id);
                
            if ($item === false) {
                throw new Exception('лекция не найдена');
            }

            (new \VideoModel())->remove($item->id);
            (new \LecturesGroupModel())->removeByLecture($item->id);
            $lectureService->delete($item->id);

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            
            throw $e;
        }
    }
    
}
