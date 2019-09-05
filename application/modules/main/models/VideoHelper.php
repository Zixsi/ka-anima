<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VideoHelper extends APP_Model
{
	public function getDetailInfo($code)
	{
		$result = $this->VideoModel->byVideoCode($code);
		if($result)
		{
			$result['source'] = null;
			$result['course'] = null;

			if($result['source_type'] === 'lecture')
			{
				if($lecture = $this->LecturesModel->getByID($result['source_id']))
				{
					$result['source'] = $lecture;
					if($course = $this->CoursesModel->getByID($lecture['course_id']))
						$result['course'] = $course; 
				}
			}
		}

		return $result;
	}

	public function checkVideoAccess($userId, $courseId, $lectureId)
	{
		$hasAccess = false;
		$groups = $this->SubscriptionModel->groupsList($userId);
		foreach($groups as $val)
		{
			if($hasAccess)
				break;

			if((bool) $val['subscr_active'] === false || (int) $courseId !== (int) $val['id'])
				continue;

			$lectures = $this->LecturesGroupModel->listForGroup($val['course_group']);
			foreach($lectures as $lectureVal)
			{
				if((int) $lectureVal['id'] === $lectureId && (int) $lectureVal['active'] === 1)
				{
					$hasAccess = true;
					break;
				}
			}
		}

		return $hasAccess;
	}
}