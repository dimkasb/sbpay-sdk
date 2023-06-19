<?php

namespace SBPay\Contract;

use SBPay\Exceptions\AccessDeniedException;
use SBPay\Exceptions\BadRequestException;
use SBPay\Exceptions\NotFoundException;
use SBPay\Exceptions\UnauthorizedException;
use SBPay\Exceptions\UnexpectedException;

interface ISBPayPayments
{

    /**
     * Approve payment order.
     * Pass $paymentMethodReferenceId and $paymentMethodName to save payment method for future use
     * (recurring payments or rebilling)
     *
     * @param int $id
     * @param string $reason
     * @param string $paymentMethod
     * @param string|null $transactionId
     * @param string|null $paymentMethodReferenceId
     * @param string|null $paymentMethodName
     * @return void
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function approveOrder(
        int $id, string $reason, string $paymentMethod, ?string $transactionId = null,
        ?string $paymentMethodReferenceId = null, ?string $paymentMethodName = null
    ): void;

    /**
     * Refund payment order
     *
     * @param int $id
     * @param string $reason
     * @return void
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function refundOrder(int $id, string $reason): void;

    /**
     * Return order details by id
     *
     * @param int $id
     * @return array
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function getOrder(int $id): array;

    /**
     * Validates request to custom payment processor
     *
     * @param array $data
     * @return void
     * @throws UnexpectedException
     */
    public function validateCustomPaymentProcessorRequest(array $data): void;

}