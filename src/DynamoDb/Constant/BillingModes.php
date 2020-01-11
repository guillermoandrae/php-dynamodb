<?php

namespace Guillermoandrae\DynamoDb\Constant;

/**
 * Controls how you are charged for read and write throughput and how you manage capacity.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#createtable
 */
final class BillingModes
{
    /**
     * AWS recommends using PROVISIONED for predictable workloads. PROVISIONED sets the billing mode to Provisioned
     * Mode.
     *
     * @var string
     */
    public const PROVISIONED = 'PROVISIONED';

    /**
     * AWS recommends using PAY_PER_REQUEST for unpredictable workloads. PAY_PER_REQUEST sets the billing mode to
     * On-Demand Mode.
     *
     * @var string
     */
    public const PAY_PER_REQUEST = 'PAY_PER_REQUEST';
}
