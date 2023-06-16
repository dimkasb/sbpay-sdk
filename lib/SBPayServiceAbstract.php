<?php

namespace SBPay;

use SBPay\Contract\ISBPayClient;

class SBPayServiceAbstract
{

    /**
     * SBPay client instance
     *
     * @var ISBPayClient
     */
    private ISBPayClient $client;

    /**
     * Construct
     *
     * @param ISBPayClient $client
     */
    public function __construct(ISBPayClient $client)
    {
        $this->client = $client;
    }

    /**
     * Return SBPay client instance
     *
     * @return ISBPayClient
     */
    protected function client(): ISBPayClient
    {
        return $this->client;
    }

}