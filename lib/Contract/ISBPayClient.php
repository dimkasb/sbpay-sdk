<?php

namespace SBPay\Contract;

use SBPay\Exceptions\AccessDeniedException;
use SBPay\Exceptions\BadRequestException;
use SBPay\Exceptions\NotFoundException;
use SBPay\Exceptions\UnauthorizedException;
use SBPay\Exceptions\UnexpectedException;

interface ISBPayClient
{

    /**
     * Return payments service
     *
     * @return ISBPayPayments
     */
    public function payments(): ISBPayPayments;

    /**
     * Return subscriptions service
     *
     * @return ISBPaySubscriptions
     */
    public function subscriptions(): ISBPaySubscriptions;

    /**
     * Make request to SBPay API
     *
     * @param string $method
     * @param string $path
     * @param string|null $body
     * @param string|null $signature
     * @param string|null $timestamp
     * @return array|null
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function request(
        string $method, string $path, ?string $body = null, ?string $signature = null, ?string $timestamp = null
    ): ?array;

    /**
     * Make signed POST request
     *
     * @param string $path
     * @param array $data
     * @return array|null
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function signedPost(
        string $path, array $data = []
    ): ?array;

    /**
     * Make signed PUT request
     *
     * @param string $path
     * @param array $data
     * @return array|null
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function signedPut(
        string $path, array $data = []
    ): ?array;

    /**
     * Make signed DELETE request
     *
     * @param string $path
     * @param array $data
     * @return array|null
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function signedDelete(
        string $path, array $data = []
    ): ?array;

    /**
     * Make signed GET request
     *
     * @param string $path
     * @param string $dataToSign
     * @return array|null
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function signedGet(
        string $path, string $dataToSign
    ): ?array;

}