<?php

class Notifications
{
	private $list = [];
	private $c;

	const INFO = 'info';
	const PRIMARY = 'primary';
	const DANGER = 'danger';
	const WARNING = 'warning';

	public function load()
	{
		$this->c = &get_instance();

		if(!$this->c->Auth->isActive())
			$this->add(self::WARNING, 'Пользователь неактивирован', '/profile/');
	}

	public function list()
	{
		return $this->list;
	}

	public function add($type, $text, $href = null, $icon = null)
	{
		$item = [
			'type' => $type,
			'text' => $text,
			'href' => $href,
			'icon' => ($icon ?? 'mdi mdi-alert-circle')
		];

		array_unshift($this->list, $item);
	}
}