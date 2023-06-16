<?php

namespace SBPay\Exceptions;

class AccessDeniedException extends Exception
{

    /**
     * Construct
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message, 403);
    }

}