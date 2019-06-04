<?php

class Jsonajax
{
	const CODE_ACCESS_DENIED = 'ACCESS_DENIED';
	const CODE_INTERNAL_ERROR = 'INTERNAL_ERROR';
	const CODE_NOT_AUTHORIZED = 'NOT_AUTHORIZED';

	const ERRORS = [
		self::CODE_ACCESS_DENIED => 'access denied',
		self::CODE_INTERNAL_ERROR => 'internal error',
		self::CODE_NOT_AUTHORIZED => 'not authorized'
	];

	public function __construct(){}

	public function result($data)
	{
		echo json_encode(['result' => $data]); die();
	}

	public function error($code, $message = '')
	{
		if(empty($code))
			$code = self::CODE_INTERNAL_ERROR;

		$data = [
			'error' => [
				'code' => $code,
				'message' => ((empty($message) && array_key_exists($code, self::ERRORS))?self::ERRORS[$code]:$message)
			]
		];

		echo json_encode($data); die();
	}
}