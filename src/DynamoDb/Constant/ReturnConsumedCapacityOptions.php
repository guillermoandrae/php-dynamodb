<?php

namespace Guillermoandrae\DynamoDb\Constant;

/**
 * Determines the level of detail about provisioned throughput consumption that is returned in the response.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#getitem
 */
final class ReturnConsumedCapacityOptions
{
    /**
     * The response will include the aggregate ConsumedCapacity for the operation, together with ConsumedCapacity for
     * each table and secondary index that was accessed.
     *
     * @var string
     */
    public const INDEXES = 'INDEXES';

    /**
     * The response will include only the aggregate ConsumedCapacity for the operation.
     *
     * @var string
     */
    public const TOTAL = 'TOTAL';

    /**
     * No ConsumedCapacity details will be included in the response.
     *
     * @var string
     */
    public const NONE = 'NONE';
}
