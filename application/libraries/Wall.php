<?php

class Wall
{
	private $c;

	function __construct()
	{
		$this->c = &get_instance();
	}

	function show($target)
	{
		$data = [];
		$data['params']= [
			'limit' => 3,
			'offset' => 0,
			'cnt' => $this->c->WallModel->allCnt($target),
			'show_more' => false
		];
		$next_offset = ($data['params']['offset'] + $data['params']['limit']);
		if($next_offset < $data['params']['cnt'])
		{
			$data['params']['offset'] = $next_offset;
			$data['params']['show_more'] = true;
		}

		$data['target_id'] = $target;
		$data['items'] = $this->c->WallModel->list($target, $data['params']['limit'], 0);

		// debug($data['params']); die();
		return $this->c->load->view('app/wall/wall', $data, true);
	}
}