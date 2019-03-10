<?php

class JsonRpcModel extends APP_Model
{
	private $request_id = null;
	private $errors = [];

	public function __construct()
	{
		$this->errors = [
			-32700	=> 'Parse error',
			-32600	=> 'Invalid Request',
			-32601	=> 'Method not found',
			-32602	=> 'Invalid params',
			-32603	=> 'Internal error',
			-32000	=> 'Not authorized',
			// -32099 => 'other error',
			// -32001 to -32098 Reserved for implementation-defined server-errors.
		];
	}

	public function parse($json)
	{
		try
		{
			$array = json_decode($json, true);
			if(!is_array($array))
			{
				$this->error(-32700);
			}

			if(!isset($array['jsonrpc']) || empty($array['jsonrpc']) || $array['jsonrpc'] !== '2.0')
			{
				$this->error(-32602);
			}

			if(!isset($array['method']) || empty($array['method']))
			{
				$this->error(-32602);
			}

			if(isset($array['id']) && !empty($array['id']))
			{
				$this->request_id = $array['id'];
			}

			return $array;
		}
		catch(Exception $e)
		{
			$this->error(-32603);
		}

		return false;
	}

	public function result($data)
	{
		$data = [
			'jsonrpc' => '2.0',
			'result' => $data,
			'id' => $this->request_id
		];

		echo json_encode($data); die();
	}

	public function error($code, $message = '')
	{
		$data = [
			'jsonrpc' => '2.0',
			'error' => [
				'code' => $code,
				//'message' => ((array_key_exists($code, $this->errors))?$this->errors[$code]:$message)
				'message' => ((empty($message) && array_key_exists($code, $this->errors))?$this->errors[$code]:$message)
			],
			'id' => $this->request_id
		];

		echo json_encode($data); die();
	}
}