<?php

class Action
{
	const LOGIN = 'LOGIN'; // авторизация
	const REGISTRATION = 'REGISTRATION'; // регистрация
	const FORGOT = 'FORGOT'; // восстановление пароля
	const SUBSCRIPTION = 'SUBSCRIPTION'; // подписка
	const SUBSCRIPTION_RENEW = 'SUBSCRIPTION_RENEW'; // обновление подписки
	const HOMEWORK_UPLOAD = 'HOMEWORK_UPLOAD'; // загрузка ДЗ
	const HOMEWORK_DOWNLOAD = 'HOMEWORK_DOWNLOAD'; // скачивание ДЗ
	const REVIEW_ADD = 'REVIEW_ADD'; // создание обзора
	const REVIEW_UPDATE = 'REVIEW_UPDATE'; // обновление обзора
	const REVIEW_DELETE = 'REVIEW_DELETE'; // удаление обзора
	const STREAM_ADD = 'STREAM_ADD'; // создание онлайн встречи
	const STREAM_UPDATE = 'STREAM_UPDATE'; // обновление онлайн встречи
	const STREAM_DELETE = 'STREAM_DELETE'; // удаление онлайн встречи
	const MAILING = 'MAILING'; // рассылка

	const LIST = [
		self::LOGIN,
		self::REGISTRATION,
		self::FORGOT,
		self::SUBSCRIPTION,
		self::SUBSCRIPTION_RENEW,
		self::HOMEWORK_UPLOAD,
		self::HOMEWORK_DOWNLOAD,
		self::REVIEW_ADD,
		self::REVIEW_UPDATE,
		self::REVIEW_DELETE,
		self::STREAM_ADD,
		self::STREAM_UPDATE,
		self::STREAM_DELETE,
		self::MAILING
	];

	private static $observers = [];

	/**
	* Отправить событие
	* @param string $action событие
	* @param array $params массив параметров
	**/
	public static function send(string $action, array $params = [])
	{
		if(empty($action) || array_key_exists($action, self::$observers) === false)
			return;

		$list = self::$observers[$action];
		foreach($list as $callback)
		{
			call_user_func_array($callback, $params);
		}
	}

	/**
	* Отправить событие
	* @param string $action событие
	* @param mixed $callback функция вызова
	**/
	public static function listen(string $action, $callback)
	{
		if(empty($action) || in_array($action, self::LIST) == false /*&& is_callable($callback) === false*/)
			return;

		self::$observers[$action][] = $callback;
	}

	public static function getDescription(string $action, array $params = [], array $item = [])
	{
		$result = '';
		switch($action)
		{
			case self::LOGIN:
				$result = 'Авторизация:<br>IP: $1<br>UserAgent:$2';
				$result = str_replace(['$1','$2'], [
						($item['ip'] ?? ''),
						($item['ua'] ?? '')
					], $result);
				break;
			case self::REGISTRATION:
				$result = 'Регистрация:<br>IP: $1<br>UserAgent:$2';
				$result = str_replace(['$1','$2'], [
						($item['ip'] ?? ''),
						($item['ua'] ?? '')
					], $result);
				break;
			case self::FORGOT:
				$result = 'Восстановление пароля:<br>IP: $1<br>UserAgent:$2';
				$result = str_replace(['$1','$2'], [
						($item['ip'] ?? ''),
						($item['ua'] ?? '')
					], $result);
				break;
			case self::SUBSCRIPTION:
				$result = 'Подписка на курс <a href="/admin/groups/$1/" target="_blank">$1</a>';
				$result = str_replace(['$1'], [($params['group_code'] ?? '')], $result);
				break;
			case self::SUBSCRIPTION_RENEW:
				$result = 'Продление подписки <a href="/admin/groups/$1/" target="_blank">$1</a>';
				$result = str_replace(['$1'], [($params['group_code'] ?? '')], $result);
				break;
			case self::HOMEWORK_UPLOAD:
				$result = 'Загрузка файла домашнего задания <a href="/admin/groups/$1/?user=$2" target="_blank">$1</a> ($3) - $4';
				$result = str_replace(['$1','$2', '$3', '$4'], [
						($params['group_code'] ?? ''),
						($item['user'] ?? ''),
						($params['lecture_name'] ?? ''),
						($params['file_name'] ?? '')
					], $result);
				break;
			case self::HOMEWORK_DOWNLOAD:
				$result = 'Скачан файла домашнего задания <a href="/admin/groups/$1/?user=$2" target="_blank">$1</a> ($3) -  <a href="/admin/users/user/$2/" target="_blank">$4</a>';
				$result = str_replace(['$1','$2', '$3', '$4'], [
						($params['group_code'] ?? ''),
						($params['user_id'] ?? ''),
						($params['lecture_name'] ?? ''),
						($params['user_name'] ?? '')
					], $result);
				break;
			case self::REVIEW_ADD:
				$result = 'Добавление ревью <a href="/admin/groups/$1/?user=$2" target="_blank">$1</a> ($3) -  <a href="/admin/users/user/$2/" target="_blank">$4</a>';
				$result = str_replace(['$1','$2', '$3', '$4'], [
						($params['group_code'] ?? ''),
						($params['user_id'] ?? ''),
						($params['lecture_name'] ?? ''),
						($params['user_name'] ?? '')
					], $result);
				break;
			case self::REVIEW_UPDATE:
				$result = 'Обновление ревью <a href="/admin/groups/$1/?user=$2" target="_blank">$1</a> ($3) -  <a href="/admin/users/user/$2/" target="_blank">$4</a>';
				$result = str_replace(['$1','$2', '$3', '$4'], [
						($params['group_code'] ?? ''),
						($params['user_id'] ?? ''),
						($params['lecture_name'] ?? ''),
						($params['user_name'] ?? '')
					], $result);
				break;
			case self::REVIEW_DELETE:
				$result = 'Удаление ревью <a href="/admin/groups/$1/?user=$2" target="_blank">$1</a> ($3) -  <a href="/admin/users/user/$2/" target="_blank">$4</a>';
				$result = str_replace(['$1','$2', '$3', '$4'], [
						($params['group_code'] ?? ''),
						($params['user_id'] ?? ''),
						($params['lecture_name'] ?? ''),
						($params['user_name'] ?? '')
					], $result);
				break;
			case self::STREAM_ADD:
				$result = 'Создание онлайн встречи <a href="/admin/streams/item/$1/" target="_blank">$2</a>';
				$result = str_replace(['$1','$2'], [
						($params['item_id'] ?? ''),
						($params['item_name'] ?? '')
					], $result);
				break;
			case self::STREAM_UPDATE:
				$result = 'Обновление онлайн встречи <a href="/admin/streams/item/$1/" target="_blank">$2</a>';
				$result = str_replace(['$1','$2'], [
						($params['item_id'] ?? ''),
						($params['item_name'] ?? '')
					], $result);
				break;
			case self::STREAM_DELETE:
				$result = 'Удаление онлайн встречи <a href="/admin/streams/item/$1/" target="_blank">$2</a>';
				$result = str_replace(['$1','$2'], [
						($params['item_id'] ?? ''),
						($params['item_name'] ?? '')
					], $result);
				break;
			default:
				// empty
				break;
		}

		return $result;
	}
}