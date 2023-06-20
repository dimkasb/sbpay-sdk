# SBPay.me PHP SDK

[![Build Status](https://github.com/dimkasb/sbpay-sdk/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/dimkasb/sbpay-sdk/actions?query=branch%3Amain)
[![Latest Stable Version](https://poser.pugx.org/sbpay/sdk/v/stable.svg)](https://packagist.org/packages/sbpay/sdk)
[![Total Downloads](https://poser.pugx.org/sbpay/sdk/downloads.svg)](https://packagist.org/packages/sbpay/sdk)
[![License](https://poser.pugx.org/sbpay/sdk/license.svg)](https://packagist.org/packages/sbpay/sdk)

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

## Examples

You can find custom payment processor example [`here`](https://github.com/dimkasb/sbpay-sdk/tree/main/example/custom_payment_processor)
