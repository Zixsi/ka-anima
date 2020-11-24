<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OptionsModel extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// стоимость подписки на год
	public static function getPriceRenewYear()
	{
		return (float) 0;
	}

	// кол-во недель доступности курса до старта
	public static function getNumberWeeksBeforeStartCourse()
	{
		return 12;
	}

	// кол-во недель доступности курса после старта
	public static function getNumberWeeksAfterStartCourse()
	{
		return 1;
	}
}