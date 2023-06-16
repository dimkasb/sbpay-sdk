<?php

namespace SBPay;

use SBPay\Exceptions\AccessDeniedException;
use SBPay\Exceptions\BadRequestException;
use SBPay\Exceptions\NotFoundException;
use SBPay\Exceptions\UnauthorizedException;
use SBPay\Exceptions\UnexpectedException;

class SBPayPayments extends SBPayServiceAbstract implements Contract\ISBPayPayments
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
    public function approveOrder(int $id, string $reason, $paymentMethod): void
    {
        $this->client()->signedPost(
            '/order/' . $id . '/approve',
            [
                'reason' => $reason,
                'paymentMethod' => $paymentMethod
            ]
        );
    }

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
    public function refundOrder(int $id, string $reason): void
    {
        $this->client()->signedPost(
            '/order/' . $id . '/refund',
            [
                'reason' => $reason,
                'makeCreditNote' => true
            ]
        );
    }

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
    public function getOrder(int $id): array
    {
        return $this->client()->signedGet(
            '/order/' . $id, $id
        );
    }

    /**
     * Validates request to custom payment processor
     *
     * @param array $data
     * @return void
     * @throws UnexpectedException
     */
    public function validateCustomPaymentProcessorRequest(array $data): void
    {
        ksort($data);

        if (!isset($data['signature'])) {
            throw new UnexpectedException('Signature is not set');
        }
        if (!isset($data['algo'])) {
            throw new UnexpectedException('Algo is not set');
        }
        if (!isset($data['timestamp'])) {
            throw new UnexpectedException('Timestamp is not set');
        }
        if (!isset($data['order_id'])) {
            throw new UnexpectedException('Order id is not set');
        }
        if (abs(strtotime($data['timestamp']) - time()) > 60 * 5) {
            throw new UnexpectedException('Timestamp is not valid');
        }
        if (in_array($data['algo'], ['sha256', 'sha512'], true) === false) {
            throw new UnexpectedException('Algo is not valid');
        }

        $signature = $data['signature'];
        unset($data['signature']);

        $expectedSignature = hash_hmac($data['algo'], implode('|', $data), $this->client()->getSecret());

        if ($signature !== $expectedSignature) {
            throw new UnexpectedException('Signature is not valid');
        }
    }
}