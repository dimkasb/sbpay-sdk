<html lang="en">
<head>
    <title>SBPay Demo</title>
</head>
<body>
<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/config.php';

// request will come from SBPay with payment details in $_POST.
// You need to validate the request and then process it.
$client = new \SBPay\SBPayClient(
    SBPAY_TOKEN, SBPAY_SECRET,
    SBPAY_MERCHANT, SBPAY_HOST
);

try {
    // Validate request that comes from SBPay
    $client->payments()->validateCustomPaymentProcessorRequest($_POST);
} catch (\SBPay\Exceptions\Exception $e) {
    // handle exception. Just show error message for demo purposes
    die($e->getMessage());
}

// Here you must implement request to your payment system
// As example redirect to PayPal
?>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="business" value="herschelgomez@xyzzyu.com">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="item_name" value="Order <?php echo $_POST['order_id'] ?>">
        <input type="hidden" name="amount" value="<?php echo $_POST['amount'] ?>">
        <input type="hidden" name="currency_code" value="<?php echo $_POST['currency'] ?>">
        <input type="hidden" name="return" value="<?php echo $_POST['return_url'] ?>">
        <input type="hidden" name="cancel_return" value="<?php echo $_POST['cancel_url'] ?>">
        <input type="hidden" name="notify_url" value="<?php echo HOST ?>/callback.php?order_id=<?php echo $_POST['order_id'] ?>">
        <input type="image" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" alt="Buy Now">
        <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
    </form>
</body>
</html>