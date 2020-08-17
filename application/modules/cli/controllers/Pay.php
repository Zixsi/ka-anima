<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends APP_Controller
{
	public function __construct()
	{

	}

	// проверка платежей
	// php index.php cli pay check
	public function check()
	{
		echo 'START'.PHP_EOL;
		$system = $this->PaySystem->select(PaySystem::YANDEX_KASSA);

		$filter = [
			'status' => TransactionsModel::STATUS_PENDING,
			'type' => TransactionsModel::TYPE_IN
		];

		$list = $this->TransactionsModel->list($filter);
//		 debug($list); die();
		foreach($list as $val)
		{
			if(empty($val['pay_system_hash']))
				continue;

			try
			{
//				$info = $system->getOrderInfo($val['pay_system_hash']);
//				if($info->getStatus() === 'succeeded')
                                if(true)
				{
					// обработка данных транзакции
					if(!empty($val['data']))
					{
						if($this->TransactionsHelper->processingData($val['data']))
							$this->TransactionsModel->update($val['id'], ['status' => TransactionsModel::STATUS_SUCCESS]);
					}
				}
			}
			catch(Exception $e)
			{
				echo $val['pay_system_hash'].' => '.$e->getMessage().PHP_EOL;
			}
		}
		echo 'END'.PHP_EOL;
	}
}
