<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeworkHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// скачать ДЗ
	public function download($group, $lecture, $user)
	{
		if($res = $this->LecturesHomeworkModel->getListUserFilesForLecture($group, $lecture, $user))
		{
			$this->zip->compression_level = 5;
			foreach($res as $val)
			{
				$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'/'.$val);
			}
			
			$this->zip->download('homework.zip');
		}
	}
}