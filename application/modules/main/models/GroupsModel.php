<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupsModel extends APP_Model
{
	private const TABLE_SUBSCRIPTION = 'subscription';

	public function __construct()
	{
		parent::__construct();
	}
}