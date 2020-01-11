<?php

namespace Guillermoandrae\DynamoDb\Constant;

/**
 * The available roles that a key attribute can assume.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#shape-keyschemaelement
 */
final class KeyTypes
{
    /**
     * The partition key; also known as the HASH attribute.
     *
     * @var string
     */
    public const HASH = 'HASH';

    /**
     * The sort key; also known as the range attribute.
     *
     * @var string
     */
    public const RANGE = 'RANGE';
}
