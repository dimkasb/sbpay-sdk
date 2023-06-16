<?php

namespace SBPay\Exceptions;

class Exception extends \Exception
{

    /**
     * Construct
     *
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 500)
    {
        parent::__construct($message, $code);
    }

}