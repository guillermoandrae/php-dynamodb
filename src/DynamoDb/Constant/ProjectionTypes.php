<?php

namespace Guillermoandrae\DynamoDb\Constant;

/**
 * The set of attributes that are projected into an index.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#shape-projection
 */
final class ProjectionTypes
{
    /**
     * All of the table attributes are projected into the index.
     *
     * @var string
     */
    public const ALL = 'ALL';

    /**
     * Only the index and primary keys are projected into the index.
     *
     * @var string
     */
    public const KEYS_ONLY = 'KEYS_ONLY';

    /**
     * Only the specified table attributes are projected into the index.
     *
     * @var string
     */
    public const INCLUDE = 'INCLUDE';
}
