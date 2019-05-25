<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserActionsModel extends APP_Model
{
	const TABLE = 'user_actions';

	const ACTION_LOGIN = 'ACTION_LOGIN'; // авторизация
	const ACTION_COURSE_SUBSCR = 'ACTION_COURSE_SUBSCR'; // подписка на курс
	const ACTION_COURSE_RENEW_SUBSCR = 'ACTION_COURSE_RENEW_SUBSCR'; // продление подписки
	const ACTION_HOMEWORK_FILE_ADD = 'ACTION_HOMEWORK_FILE_ADD'; // загрузка файла ДЗ
	const ACTION_HOMEWORK_FILE_DOWNLOAD = 'ACTION_HOMEWORK_FILE_DOWNLOAD'; // скачивание файла ДЗ
	const ACTION_REVIEW_ADD = 'ACTION_REVIEW_ADD'; // добавление ревью
	const ACTION_REVIEW_DEL = 'ACTION_REVIEW_DEL'; // удаление ревью
	const ACTION_STREAM_ADD = 'ACTION_STREAM_ADD'; // создание онлайн встречи
	const ACTION_STREAM_EDIT = 'ACTION_STREAM_EDIT'; // редактирование онлайн встречи
	const ACTION_PAY_ADD_FOUNDS = 'ACTION_PAY_ADD_FOUNDS'; // пополнение счета 

	const ACTIONS = [
		self::ACTION_LOGIN => ['description' => 'Авторизация'],
		self::ACTION_COURSE_SUBSCR => ['description' => 'Подписка на курс'],
		self::ACTION_COURSE_RENEW_SUBSCR => ['description' => 'Продление подписки'],
		self::ACTION_HOMEWORK_FILE_ADD => ['description' => 'Загрузка файла домашнего задания'],
		self::ACTION_HOMEWORK_FILE_DOWNLOAD => ['description' => 'Скачан файла домашнего задания'],
		self::ACTION_REVIEW_ADD => ['description' => 'Добавление ревью'],
		self::ACTION_REVIEW_DEL => ['description' => 'Удаление ревью'],
		self::ACTION_STREAM_ADD => ['description' => 'Создание онлайн встречи'],
		self::ACTION_STREAM_EDIT => ['description' => 'Редактирование онлайн встречи'],
		self::ACTION_PAY_ADD_FOUNDS => ['description' => 'Пополнение баланса'],
		// self::TMP => ['description' => ''],
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		if($this->db->insert(self::TABLE, $data))
			return true;

		return false;
	}

	public function update($hash, $data = [])
	{
		return false;
	}

	public function delete($id)
	{
		return false;
	}

	public function getByHash($hash)
	{
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE hash = ?';
		if($res = $this->db->query($sql, [$id]))
			return $res->row_array();

		return false;
	}

	public function list($filter = [], $order = 'DESC')
	{
		$bind = [];
		$sql_where = [];

		if(isset($filter['user']))
		{
			$sql_where[] = 'user = ?';
			$bind[] = (int) $filter['user'];
		}

		if(count($sql_where))
			$sql_where = 'WHERE '.implode(' AND ', $sql_where);

		$sql = 'SELECT * FROM '.self::TABLE.' '.$sql_where.' ORDER BY date '.$order;
		if($res = $this->db->query($sql, $bind))
		{
			return $res->result_array();
		}

		return [];
	}

	public function listByUser($user, $order = 'DESC')
	{
		return $this->list(['user' => $user], $order);
	}

	public function hash($user, $action)
	{
		return md5($user.$action.time());
	}
}