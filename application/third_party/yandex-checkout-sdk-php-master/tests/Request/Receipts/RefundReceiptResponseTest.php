<?php

namespace Tests\YandexCheckout\Request\Receipts;

use YandexCheckout\Helpers\Random;
use YandexCheckout\Request\Receipts\RefundReceiptResponse;

require_once __DIR__ . '/AbstractReceiptResponseTest.php';

class RefundReceiptResponseTest extends AbstractReceiptResponseTest
{
    protected $type = 'refund';

    protected function getTestInstance($options)
    {
        return new RefundReceiptResponse($options);
    }

    protected function addSpecificProperties($options)
    {
        $options['refund_id'] = Random::str(36, 36);
        return $options;
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testSpecificProperties($options)
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['refund_id'], $instance->getRefundId());
    }
}