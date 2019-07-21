<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OptionsModel extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// стоимость подписки на год
	public function getPriceRenewYear()
	{
		return (float) 500;
	}
}