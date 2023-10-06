# SBPay.me PHP SDK

[![Build Status](https://github.com/dimkasb/sbpay-sdk/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/dimkasb/sbpay-sdk/actions?query=branch%3Amain)
[![Latest Stable Version](https://poser.pugx.org/sbpay/sdk/v/stable.svg)](https://packagist.org/packages/sbpay/sdk)
[![Total Downloads](https://poser.pugx.org/sbpay/sdk/downloads.svg)](https://packagist.org/packages/sbpay/sdk)
[![License](https://poser.pugx.org/sbpay/sdk/license.svg)](https://packagist.org/packages/sbpay/sdk)
[![PHP Version Require](http://poser.pugx.org/sbpay/sdk/require/php)](https://packagist.org/packages/sbpay/sdk)


The SBPay.me PHP library provides convenient access to the SBPay.me API from
applications written in the PHP language.

## Requirements

PHP 8.1.0 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require sbpay/sdk
```

## Dependencies

The bindings require the following extensions in order to work properly:

- [`curl`](https://secure.php.net/manual/en/book.curl.php), although you can use your own non-cURL client if you prefer
- [`guzzlehttp/guzzle`](https://packagist.org/packages/guzzlehttp/guzzle), although you can use your own РЕЕЗ client if you prefer

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

## Getting Started

Simple usage looks like:

```php
$client = new \SBPay\SBPayClient(SBPAY_TOKEN, SBPAY_SECRET ,SBPAY_MERCHANT, SBPAY_HOST);
$order = $client->payments()->getOrder($orderId);
```

## Reference

### Payments

To access payments API methods, get `payments` object from `SBPayClient` object:

```php
$payments = $client->payments();
```
Available methods:

| Method                                | Description                                                                                                                                              |
|---------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------|
| getOrder                              | Returns order details by Order ID                                                                                                                        |
| approveOrder                          | Approve payment order.<br/>Pass $paymentMethodReferenceId and $paymentMethodName to save payment method for future use (recurring payments or rebilling) |
| refundOrder                           | Refund payment order                                                                                                                                     |
| validateCustomPaymentProcessorRequest | Validate custom payment processor request. (Required for implementing custom payment processor)                                                          |

### Subscriptions

To access subscriptions API methods, get `subscriptions` object from `SBPayClient` object:

```php
$subscriptions = $client->subscriptions();
```

Available methods:

| Method                   | Description                                                  |
|--------------------------|--------------------------------------------------------------|
| getCustomerSubscriptions | Returns customer subscriptions list by Customer reference ID |

### Custom payment processor callbacks

Using SBPay.me, you have the flexibility to integrate a personalized payment processor. 
Whenever SBPay needs to initiate a specific action, it will trigger a callback to the URL you have designated. You can configure these callback URLs within your SBPay.me account settings.

Here is a list of the callback URLs you can configure:

* Payment form URL. You will receive POST request from browser with following data:

| Parameter name        | Description                                                                                       |
|-----------------------|---------------------------------------------------------------------------------------------------|
| order_id              | Order id in SBPay                                                                                 |
| amount                | Amount                                                                                            |
| currency              | Currency                                                                                          |
| customer_id           | Customer reference id                                                                             |
| save_payment_method   | 1 if customer selected to store payment method/0 if customer selected to not store payment method |
| recurring_period_type | Recurring period type for recurring payments only (year/month/week/day)                           |
| recurring_period      | Recurring period for recurring payments only (number)                                             |
| return_url            | Return URL (you should redirect customer here after payment)                                      |
| cancel_url            | Cancel URL (you should redirect customer here if customer cancels payment)                        |
| timestamp             | Time stamp of request (to validate signature)                                                     |
| algo                  | Algorithm of signature                                                                            |
| signature             | Signature to validate request                                                                     |

* Rebilling URL. You will receive HTTP request from our servers, when we need to charge customer again (when he selected to pay with Vaulted payment method or when it is time to charge recurring payment) with following data:

| Parameter name              | Description                                                                      |
|-----------------------------|----------------------------------------------------------------------------------|
| order_id                    | Order id in SBPay                                                                |
| amount                      | Amount                                                                           |
| currency                    | Currency                                                                         |
| customer_id                 | Customer reference id                                                            |
| payment_method_reference_id | Payment method reference id (You should pass it during confirm initial payment) |
| timestamp                   | Time stamp of request (to validate signature)                                    |
| algo                        | Algorithm of signature                                                           |
| signature                   | Signature to validate request                                                    |

* Delete payment method URL. You will receive HTTP request from our servers, when customer decided to delete Vaulted payment method (remove card, etc.) with following data:

| Parameter name              | Description                                                                     |
|-----------------------------|---------------------------------------------------------------------------------|
| customer_id                 | Customer reference id                                                           |
| payment_method_reference_id | Payment method reference id (You should pass it during confirm initial payment) |
| timestamp                   | Time stamp of request (to validate signature)                                   |
| algo                        | Algorithm of signature                                                          |
| signature                   | Signature to validate request                                                   |

* Refund URL. You will receive HTTP request from our servers, when user of SBPay (SimplyBook) makes refund. You will receive following data:

| Parameter name  | Description                                                                                       |
|-----------------|---------------------------------------------------------------------------------------------------|
| order_id        | Order id in SBPay                                                                                 |
| amount          | Amount                                                                                            |
| currency        | Currency                                                                                          |
| transaction_id  | Transaction ID of payment (you should pass it during payment confirmation)                        |
| timestamp       | Time stamp of request (to validate signature)                                                     |
| algo            | Algorithm of signature                                                                            |
| signature       | Signature to validate request                                                                     |

You can use SDK to validate request:

```php
$client = new \SBPay\SBPayClient(SBPAY_TOKEN, SBPAY_SECRET ,SBPAY_MERCHANT, SBPAY_HOST);
$client->payments()->validateCustomPaymentProcessorRequest($_POST);
```

## Examples

You can find custom payment processor example [`here`](https://github.com/dimkasb/sbpay-sdk/tree/main/example/custom_payment_processor)

## Documentation for API Endpoints

You can find API documentation [`here`](https://app.sbpay.me/en/api-documentation)