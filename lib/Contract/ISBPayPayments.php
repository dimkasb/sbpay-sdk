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
     * Approve payment order
     *
     * @param int $id
     * @param string $reason
     * @param $paymentMethod
     * @return void
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function approveOrder(int $id, string $reason, $paymentMethod): void;

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
     */
    public function validateCustomPaymentProcessorRequest(array $data): void;

}