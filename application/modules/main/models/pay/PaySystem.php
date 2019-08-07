<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaySystem extends APP_Model
{
	const YANDEX_KASSA = 'YANDEX_KASSA';

	private $system = null;

	public function __construct()
	{
		// empty
	}

	// выбор системы оплаты
	public function select($system)
	{
		$result = null;
		switch($system)
		{
			case self::YANDEX_KASSA:
				$result = $this->yakassa;
				break;
			default:
				// empty
		}

		return $result;
	}
}