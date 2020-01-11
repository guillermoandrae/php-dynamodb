<?php

namespace Guillermoandrae\DynamoDb\Constant;

/**
 * The attributes to be returned in the result. You can retrieve all item attributes, specific item attributes, the
 * count of matching items, or in the case of an index, some or all of the attributes projected into the index.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#query
 */
final class Select
{
    /**
     * Returns all of the item attributes from the specified table or index.
     *
     * @var string
     */
    public const ALL_ATTRIBUTES = 'ALL_ATTRIBUTES';

    /**
     * Allowed only when querying an index. Retrieves all attributes that have been projected into the index.
     *
     * @var string
     */
    public const ALL_PROJECTED_ATTRIBUTES = 'ALL_PROJECTED_ATTRIBUTES';

    /**
     * Returns only the attributes listed in AttributesToGet.
     *
     * @var string
     */
    public const SPECIFIC_ATTRIBUTES = 'SPECIFIC_ATTRIBUTES';

    /**
     * Returns the number of matching items, rather than the matching items themselves.
     *
     * @var string
     */
    public const COUNT = 'COUNT';
}
