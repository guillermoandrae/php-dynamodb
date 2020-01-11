<?php

namespace Guillermoandrae\DynamoDb\Constant;

/**
 * Data types for attributes.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#shape-attributedefinition
 */
final class AttributeTypes
{
    /**
     * The string attribute type.
     *
     * @var string
     */
    public const STRING = 'S';

    /**
     * The number attribute type.
     *
     * @var string
     */
    public const NUMBER = 'N';

    /**
     * The binary attribute type.
     *
     * @var string
     */
    public const BINARY = 'B';
}
