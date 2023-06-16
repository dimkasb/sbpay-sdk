<?php

namespace SBPay;

use SBPay\Exceptions\AccessDeniedException;
use SBPay\Exceptions\BadRequestException;
use SBPay\Exceptions\NotFoundException;
use SBPay\Exceptions\UnauthorizedException;
use SBPay\Exceptions\UnexpectedException;

class SBPaySubscriptions extends SBPayServiceAbstract implements Contract\ISBPaySubscriptions
{

    /**
     * Return customer subscriptions
     *
     * @param string $projectId
     * @param string $customerId
     * @return array
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function getCustomerSubscriptions(string $projectId, string $customerId): array
    {
        return $this->client()->signedGet(
            sprintf('/%s/subscriptions/%s', $projectId, $customerId),
            implode('|', [$projectId, $customerId])
        );
    }
}