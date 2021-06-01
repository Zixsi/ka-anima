<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class VideoHelper extends APP_Model
{

    public function getDetailInfo($code)
    {
        $result = $this->VideoModel->byVideoCode($code);
        if ($result) {
            $result['source'] = null;
            $result['course'] = null;

            if ($result['source_type'] === 'lecture') {
                $lecture = (new \App\Service\LectureService())->getById((int) $result['source_id']);
                    
                if ($lecture) {
                    $result['source'] = $lecture->toViewArray();
                    
                    if ($course = $this->CoursesModel->getByID($lecture->courseId)) {
                        $result['course'] = $course;
                    }
                }
            }
        }

        return $result;
    }

    public function checkVideoAccess($userId, $courseId, $lectureId)
    {
        $hasAccess = false;
        $groups = $this->SubscriptionModel->groupsList($userId);
        foreach ($groups as $val) {
            if ($hasAccess)
                break;

            if ((bool) $val['subscr_active'] === false || (int) $courseId !== (int) $val['id'])
                continue;

            $lectures = $this->LecturesGroupModel->listForGroup($val['course_group']);
            foreach ($lectures as $lectureVal) {
                if ((int) $lectureVal['id'] === $lectureId && (int) $lectureVal['active'] === 1) {
                    $hasAccess = true;
                    break;
                }
            }
        }

        return $hasAccess;
    }

    public function prepareVideoList(&$data)
    {
        if (is_array($data)) {
            foreach ($data as &$row) {
                if (isset($row['duration']))
                    $row['duration_f'] = time2hours($row['duration']);
            }
        }
    }

    public function add(array $params)
    {
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($params);

        if ($this->form_validation->run('video') === false)
            throw new Exception($this->form_validation->error_string(), 1);

        return $this->VideoModel->add($params);
    }

    public function update(int $id, array $params)
    {
        return $this->VideoModel->update($id, $params);
    }

    public function getTotalDuration($data)
    {
        $result = 0;
        if (is_array($data)) {
            foreach ($data as $row) {
                if (isset($row['duration']))
                    $result += (int) $row['duration'];
            }
        }

        return $result;
    }

}
