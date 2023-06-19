<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/config.php';

// Important! Here you must implement your own payment validation logic!
// We do not include this logic in this example because it is not related to SBPay SDK.
// Here we just approve payment.

// request will come from SBPay with payment details in $_POST.
// You need to validate the request and then process it.
$client = new \SBPay\SBPayClient(
    SBPAY_TOKEN, SBPAY_SECRET,
    SBPAY_MERCHANT, SBPAY_HOST
);
$client->payments()->approveOrder(
    $_GET['order_id'], 'Payment processed by custom processor', 'Custom PayPal',
    'tr_1', 'pm_1', '4242***1'
);