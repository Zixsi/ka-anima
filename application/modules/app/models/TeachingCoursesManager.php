<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeachingCoursesManager extends APP_Model
{
	public function Add()
	{
		try
		{
			$data = $this->input->post(null, true);
			$this->form_validation->set_data($data);

			if($this->form_validation->run('course_add') == FALSE)
			{
				throw new Exception($this->form_validation->error_string(), 1);
			}
			
			return true;
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}
}