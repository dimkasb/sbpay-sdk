<?php

namespace SBPay;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;

class SBPayHttpClientFactory
{

    /**
     * HTTP Client instance
     *
     * @var ClientInterface|null
     */
    private static ?ClientInterface $client = null;

    public static function create(): ClientInterface
    {
        if (!self::$client) {
            self::$client = new Client();
        }
        return self::$client;
    }

}