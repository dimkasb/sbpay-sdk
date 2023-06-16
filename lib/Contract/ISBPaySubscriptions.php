<?php

namespace SBPay\Contract;

interface ISBPaySubscriptions
{

    /**
     * Return customer subscriptions
     *
     * @param string $projectId
     * @param string $customerId
     * @return array
     */
    public function getCustomerSubscriptions(string $projectId, string $customerId): array;

}