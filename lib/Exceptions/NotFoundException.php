<?php

namespace SBPay\Exceptions;

class NotFoundException extends Exception
{

    /**
     * Construct
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message, 404);
    }

}