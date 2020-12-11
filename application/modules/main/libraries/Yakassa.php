<?php

require_once APPPATH.'third_party/yookassa-sdk-php-master/lib/autoload.php';

use YooKassa\Client;

class Yakassa
{
//	 const SHOP_ID = 596353;
//	 const SHOP_SECRET = 'test_jE756N0-1ucAcDX0oFSNG8xAyZvaxDQNBPBlyuTwelM';

	const SHOP_ID = 593472;
	const SHOP_SECRET = 'live_fd0W786Z6pfmU89V5k7uBkB1yLUgpF0WC1GHHFKkqKY';
	
	private $client;
	private $paymentParams = [
		'description' => '',
		'amount' => [
			'value' => '',
			'currency' => 'RUB'
		],
		'confirmation' => [
			'type' => 'redirect',
			'return_url' => ''
		],
		'capture' => true,
		'receipt' => [
			'customer' => [],
			'items' => [],
			'tax_system_code' => 2
		],
		'metadata' => []
	];

	private $orderId = null;
	private $payUrl = null;

	public function __construct()
	{
		$this->client = new Client();
		$this->client->setAuth(self::SHOP_ID, self::SHOP_SECRET);
	}

	public function setCustomer($full_name, $email, $phone = null)
	{
		$customer = [
			'full_name' =>  $full_name,
			'email' => $email
		];

		if(!empty($phone))
			$customer['phone'] = $phone;

		$this->paymentParams['receipt']['customer'] = $customer;
	}

	public function setReturnUrl($url)
	{
		$this->paymentParams['confirmation']['return_url'] = $url;
	}

	public function setBase($amount, $description = '')
	{
		$this->paymentParams['amount']['value'] = (string) $amount;
		$this->paymentParams['description'] = $description;
	}

	public function setItems($data)
	{
		$items = [];
		foreach($data as $val)
		{
			$items[] = [
				'description' => $val['description'],
				'quantity' => '1.00',
				'amount' => [
					'value' => (string) $val['price'],
					'currency' => 'RUB'
				],
				'vat_code' => 1,
				'payment_subject' => 'service',
				'payment_mode' => 'full_payment'
			];
		}

		$this->paymentParams['receipt']['items'] = $items;
	}

	public function setMeta($data)
	{
		if(is_array($data))
			$this->paymentParams['metadata'] = $data;
	}

	public function run()
	{
		// debug(var_dump($this->paymentParams)); die();
		$payment = $this->client->createPayment($this->paymentParams);

		$this->orderId = $payment->getId();
		$this->payUrl = $payment->getConfirmation()->getConfirmationUrl();
	}

	public function getOrderId()
	{
		return $this->orderId;
	}

	public function getPayUrl()
	{
		return $this->payUrl;
	}

	public function getOrderInfo($id)
	{
		return $this->client->getPaymentInfo($id);
	}
}