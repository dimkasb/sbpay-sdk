<?php

namespace SBPay\Exceptions;

class BadRequestException extends Exception
{

    /**
     * Errors
     *
     * @var array|null
     */
    private ?array $errors;

    /**
     * Construct
     *
     * @param string $message
     * @param ?array $errors
     */
    public function __construct(string $message, ?array $errors)
    {
        $this->errors = $errors;

        parent::__construct($message, 400);
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

}